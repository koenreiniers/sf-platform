<?php
namespace Raw\Search;

use Raw\Search\Index\IndexConfigProvider;

class IndexRegistry
{
    /**
     * @var SearchIndex[]
     */
    private $indexes = [];

    /**
     * @var IndexConfigProvider
     */
    private $configProvider;

    public function __construct(IndexConfigProvider $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    private function createSearchIndex($name)
    {
        return new SearchIndex($name, $this->configProvider->getConfig($name));
    }

    /**
     * @param $name
     * @return SearchIndex
     */
    public function getIndex($name)
    {
        if(!isset($this->indexes[$name])) {
            $this->indexes[$name] = $this->createSearchIndex($name);
        }
        return $this->indexes[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasIndex($name)
    {
        return $this->configProvider->hasConfig($name);
    }
}