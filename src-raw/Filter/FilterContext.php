<?php
namespace Raw\Filter;

use Raw\Filter\Context\FilterDefinition;

class FilterContext
{
    /**
     * @var FilterDefinition[]
     */
    protected $definitions = [];

    /**
     * @var FilterAdapter
     */
    protected $adapter;

    /**
     * @var FilterRegistry
     */
    protected $registry;

    /**
     * @var FilterStorageInterface
     */
    protected $storage;

    /**
     * @return FilterStorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @return FilterRegistry
     */
    public function getRegistry()
    {
        return $this->registry;
    }


    /**
     * @return FilterAdapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param string $name
     * @return FilterDefinition
     */
    public function findDefinition($name)
    {
        return $this->definitions[$name];
    }

    /**
     * @return FilterDefinition[]
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }
}