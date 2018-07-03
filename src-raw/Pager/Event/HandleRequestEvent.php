<?php
namespace Raw\Pager\Event;

use Raw\Pager\Pager;
use Symfony\Component\EventDispatcher\Event;

class HandleRequestEvent extends PagerEvent
{
    /**
     * @var mixed
     */
    private $request;

    /**
     * HandleRequestEvent constructor.
     * @param Pager $pager
     * @param mixed $request
     */
    public function __construct(Pager $pager, $request)
    {
        parent::__construct($pager);
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }


}