<?php
namespace Raw\Component\Grid\DataSource;

class ResultRecord implements \ArrayAccess
{
    /**
     * @var array
     */
    private $record;

    public function toArray()
    {
        return $this->record;
    }

    /**
     * ResultRecord constructor.
     * @param array $record
     */
    public function __construct(array $record)
    {
        $this->record = $record;
    }

    public function offsetUnset($offset)
    {
        unset($this->record[$offset]);
    }

    public function offsetExists($offset)
    {
        return isset($this->record[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->record[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->record[$offset] = $value;
    }
}