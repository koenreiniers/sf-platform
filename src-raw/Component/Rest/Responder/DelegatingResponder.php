<?php
namespace Raw\Component\Rest\Responder;

use Raw\Component\Rest\ResponderInterface;

class DelegatingResponder implements ResponderInterface
{
    /**
     * @var ResponderInterface[]
     */
    private $responders;

    /**
     * DelegatingResponder constructor.
     * @param \Raw\Component\Rest\ResponderInterface[] $responders
     */
    public function __construct(array $responders)
    {
        $this->responders = $responders;
    }

    public function respond($payload)
    {
        $responder = $this->findSupportedResponder($payload);
        return $responder->respond($payload);
    }

    /**
     * @param $payload
     * @return null|ResponderInterface
     */
    private function findSupportedResponder($payload)
    {
        foreach($this->responders as $responder) {
            if($responder->supports($payload)) {
                return $responder;
            }
        }
        return null;
    }

    public function supports($payload)
    {
        return $this->findSupportedResponder($payload) !== null;
    }
}