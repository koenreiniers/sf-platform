<?php
namespace Raw\Search;

use Raw\Search\Resource\ResourceTypeInterface;

class ResourceRegistry
{
    /**
     * @var ResourceTypeInterface[]
     */
    private $types = [];

    /**
     * ResourceRegistry constructor.
     * @param Resource\ResourceTypeInterface[] $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @param string $name
     * @return ResourceTypeInterface
     * @throws \Exception
     */
    public function getType($name)
    {
        if(!$this->hasType($name)) {
            throw new \Exception(sprintf('Resource type "%s" does not exist', $name));
        }
        return $this->types[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasType($name)
    {
        return isset($this->types[$name]);
    }
}