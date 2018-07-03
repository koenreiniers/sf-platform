<?php
namespace Raw\Component\Grid\Mapping;

class GridMetadata implements \Serializable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $properties = [];

    public function serialize()
    {
        return serialize([
            $this->name, $this->properties,
        ]);
    }

    public function unserialize($serialized)
    {
        list($this->name, $this->properties) = unserialize($serialized);
    }

    /**
     * GridMetadata constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->properties = [
            'identifier' => 'id',
        ];
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getProperty($name)
    {
        return $this->properties[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasProperty($name)
    {
        return isset($this->properties[$name]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}