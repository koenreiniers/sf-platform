<?php
namespace Raw\Component\Admin\Layout\Definition;

class ArrayNode extends Node implements \ArrayAccess, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $children = [];

    public function addChild(Node $node)
    {
        if($node->getName() !== null) {
            $this->children[$node->getName()] = $node;
        } else {
            $this->children[] = $node;
        }

        return $node;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->children);
    }

    public function offsetUnset($offset)
    {
        throw new \Exception();
    }
    public function offsetGet($offset)
    {
        return $this->children[$offset];
    }
    public function offsetSet($offset, $value)
    {
        throw new \Exception();
    }
    public function offsetExists($offset)
    {
        return isset($this->children[$offset]);
    }
}