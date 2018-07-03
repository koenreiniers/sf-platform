<?php
namespace Raw\Pager;

use Raw\Pager\Adapter\AdapterInterface;
use Raw\Pager\RequestHandler\RequestHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class PagerConfig
{
    /**
     * @var PagerFactory
     */
    protected $factory;

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var string|null
     */
    protected $namespace;

    /**
     * @var int
     */
    protected $maxPageSize = 100;

    /**
     * @var RequestHandlerInterface
     */
    protected $requestHandler;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }



    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return PagerFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @return null|string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return int
     */
    public function getMaxPageSize()
    {
        return $this->maxPageSize;
    }

    /**
     * @return RequestHandlerInterface
     */
    public function getRequestHandler()
    {
        return $this->requestHandler;
    }
}