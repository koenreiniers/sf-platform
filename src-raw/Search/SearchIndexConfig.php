<?php
namespace Raw\Search;

use Raw\Search\Hydrator\HydratorInterface;
use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Index;

class SearchIndexConfig
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $target;

    /**
     * @var HydratorInterface
     */
    protected $defaultHydrator;

    /**
     * @var bool
     */
    protected $realtime;


    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var SearchDriverInterface
     */
    protected $driver;

    /**
     * @var array
     */
    protected $driverOptions = [];

    /**
     * @return SearchDriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return array
     */
    public function getDriverOptions()
    {
        return $this->driverOptions;
    }

    /**
     * @return Registry
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return HydratorInterface
     */
    public function getDefaultHydrator()
    {
        return $this->defaultHydrator;
    }

    /**
     * @return boolean
     */
    public function isRealtime()
    {
        return $this->realtime;
    }
}