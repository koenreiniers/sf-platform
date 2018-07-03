<?php
namespace Raw\Pager\RequestHandler;

use Raw\Pager\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class HttpFoundationRequestHandler implements RequestHandlerInterface
{
    protected $sessionKey = 'raw_pager';

    protected function getSessionData(Pager $pager, Request $request)
    {
        $session = $request->getSession();
        $ns = $pager->getNamespace();

        $allSessionData = $session->get($this->sessionKey, []);

        $sessionData = isset($allSessionData[$ns]) ? $allSessionData[$ns] : [];

        return $sessionData;
    }

    protected function getRequestData(Pager $pager, Request $request)
    {
        $ns = $pager->getNamespace();
        $requestData = [];
        if($ns !== null) {
            $requestData = $request->get($ns, []);
        } else {
            $parameterNames = ['page', 'pageSize'];
            foreach($parameterNames as $parameterName) {
                $value = $request->get($parameterName);
                if($value !== null) {
                    $requestData[$parameterName] = $value;
                }
            }
        }
        return $requestData;
    }



    protected function getCombinedData(Pager $pager, Request $request)
    {
        $requestData = $this->getRequestData($pager, $request);
        $sessionData = $this->getSessionData($pager, $request);
        $data = array_merge($sessionData, $requestData);
        return $data;
    }

    /**
     * @param Pager $pager
     * @param Request $request
     */
    public function handle(Pager $pager, $request = null)
    {
        if($request === null) {
            return;
        }

        $data = $this->getCombinedData($pager, $request);

        if(isset($data['page'])) {
            $pager->setCurrentPage($data['page']);
        }
        if(isset($data['pageSize'])) {
            $pager->setPageSize($data['pageSize']);
        }
    }

    public function terminate(Pager $pager, $request = null)
    {
        if($request === null) {
            return;
        }
        $this->updateSessionData($pager, $request, [
            'page' => $pager->getCurrentPage(),
            'pageSize' => $pager->getPageSize(),
        ]);
    }

    protected function updateSessionData(Pager $pager, Request $request, array $data)
    {
        $session = $request->getSession();
        $ns = $pager->getNamespace();
        $allSessionData = $session->get($this->sessionKey, []);
        $allSessionData[$ns] = $data;
        $session->set($this->sessionKey, $allSessionData);
    }
}