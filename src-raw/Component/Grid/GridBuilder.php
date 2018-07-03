<?php
namespace Raw\Component\Grid;

use Raw\Component\Grid\DataSource\DataSource;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\ImmutableEventDispatcher;

class GridBuilder extends Grid
{
    /**
     * @var bool
     */
    private $locked = false;

    private function verifyNotLocked()
    {
        if($this->locked) {
            throw new \Exception('Grid is locked');
        }
    }

    public function getGrid()
    {
        $grid = clone $this;
        $grid->locked = true;
        $grid->eventDispatcher = new ImmutableEventDispatcher($grid->eventDispatcher);
        return $grid;
    }

    /**
     * @param DataSource $dataSource
     * @return Grid
     */
    public function setDataSource(DataSource $dataSource)
    {
        $this->verifyNotLocked();
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * @param string $eventName
     * @param callable $listener
     * @param int $priority
     * @return $this
     */
    public function addEventListener($eventName, $listener, $priority = 0)
    {
        $this->verifyNotLocked();
        $this->eventDispatcher->addListener($eventName, $listener, $priority);
        return $this;
    }

    /**
     * @param EventSubscriberInterface $subscriber
     * @return $this
     * @throws \Exception
     */
    public function addEventSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->verifyNotLocked();
        $this->eventDispatcher->addSubscriber($subscriber);
        return $this;
    }
}