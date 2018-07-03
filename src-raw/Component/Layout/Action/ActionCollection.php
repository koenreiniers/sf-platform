<?php
namespace Raw\Component\Layout\Action;

class ActionCollection implements \IteratorAggregate, \ArrayAccess
{
    /**
     * @var Action[]
     */
    protected $actions = [];

    /**
     * ActionCollection constructor.
     * @param Action[] $actions
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }
    public function offsetGet($offset)
    {
        return $this->actions[$offset];
        // TODO: Implement offsetGet() method.
    }
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
    public function offsetExists($offset)
    {
        return isset($this->actions[$offset]);
        // TODO: Implement offsetExists() method.
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->actions);
    }
}