<?php
namespace Raw\Pager;

use Raw\Pager\Adapter\AdapterInterface;
use Raw\Pager\Adapter\ForkAdapter;
use Raw\Pager\Event\BuildPagerEvent;
use Raw\Pager\Event\PagerEvent;
use Raw\Pager\RequestHandler\NativeRequestHandler;
use Raw\Pager\RequestHandler\RequestHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\ImmutableEventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class PagerBuilder extends PagerConfig
{
    private $built = false;

    protected function verifyNotBuilt()
    {
        if($this->built) {
            throw new \Exception('Pager has already been built');
        }
    }

    /**
     * PagerConfig constructor.
     * @param PagerFactory $factory
     * @param AdapterInterface $adapter
     * @param EventDispatcherInterface $eventDispatcher
     * @param array $options
     */
    public function __construct(PagerFactory $factory, AdapterInterface $adapter, EventDispatcherInterface $eventDispatcher, array $options)
    {
        $this->factory = $factory;
        $this->adapter = $adapter;
        $this->eventDispatcher = $eventDispatcher;
        $this->options = $options;
    }

    public function getPager()
    {
        $config = clone $this;
        $config->getEventDispatcher()->dispatch(PagerEvents::BUILD, new BuildPagerEvent($config));
        $config->built = true;
        $pager = new Pager($config);
        return $pager;
    }

    /**
     * @param string $eventName
     * @param callable $listener
     * @param int $priority
     * @return $this
     */
    public function addEventListener($eventName, $listener, $priority = 0)
    {
        $this->eventDispatcher->addListener($eventName, $listener, $priority);
        return $this;
    }

    /**
     * @param EventSubscriberInterface $subscriber
     * @return $this
     */
    public function addEventSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->eventDispatcher->addSubscriber($subscriber);
        return $this;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        if ($this->built && !$this->eventDispatcher instanceof ImmutableEventDispatcher) {
            $this->dispatcher = new ImmutableEventDispatcher($this->eventDispatcher);
        }
        return $this->eventDispatcher;
    }

//
//    public function setAdapter(AdapterInterface $adapter)
//    {
//        $this->adapter = $adapter;
//        return $this;
//    }

    public function setMaxPageSize($maxPageSize)
    {
        $this->verifyNotBuilt();
        $this->maxPageSize = $maxPageSize;
        return $this;
    }

    public function setNamespace($namespace)
    {
        $this->verifyNotBuilt();
        $this->namespace = $namespace;
        return $this;
    }

    public function setRequestHandler(RequestHandlerInterface $requestHandler)
    {
        $this->verifyNotBuilt();
        $this->requestHandler = $requestHandler;
        return $this;
    }
}