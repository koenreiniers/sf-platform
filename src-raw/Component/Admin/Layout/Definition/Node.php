<?php
namespace Raw\Component\Admin\Layout\Definition;

class Node
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Node|null
     */
    protected $parent;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Layout constructor.
     * @param string $name
     * @param null|Node $parent
     */
    public function __construct($name, $parent = null)
    {
        $this->name = $name;
        $this->parent = $parent;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function getAttribute($name)
    {
        return $this->attributes[$name];
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return null|Node
     */
    public function getParent()
    {
        return $this->parent;
    }
}