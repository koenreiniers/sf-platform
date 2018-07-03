<?php
namespace Raw\Pager\RequestHandler;

use Raw\Pager\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class NativeRequestHandler implements RequestHandlerInterface
{
    /**
     * @var PropertyAccessorInterface
     */
    protected $accessor;

    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param Pager $pager
     * @param null $request
     */
    public function handle(Pager $pager, $request = null)
    {

        $data = $_GET;
        $ns = $pager->getNamespace();
        if($ns !== null) {
            $data = isset($data[$ns]) ? $data[$ns] : [];
        }

        if(isset($data['page'])) {
            $pager->setCurrentPage($data['page']);
        }
        if(isset($data['pageSize'])) {
            $pager->setPageSize($data['pageSize']);
        }
    }

    public function terminate(Pager $pager, $request = null)
    {

    }
}