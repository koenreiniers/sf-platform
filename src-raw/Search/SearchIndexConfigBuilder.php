<?php
namespace Raw\Search;

use Raw\Search\Hydrator\HydratorInterface;
use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Index;

class SearchIndexConfigBuilder extends SearchIndexConfig
{
    protected function verifyNotLocked()
    {
        if($this->locked) {
            throw new \Exception('Config cannot be changed after it has been built');
        }
    }

    private $locked = false;

    public function setRegistry(Registry $registry)
    {
        $this->verifyNotLocked();
        $this->registry = $registry;
        return $this;
    }

    public function setDriverOptions(array $options)
    {
        $this->verifyNotLocked();

        $this->driverOptions = $options;
        return $this;
    }

    public function setDriver(SearchDriverInterface $driver)
    {
        $this->verifyNotLocked();
        $this->driver = $driver;
        return $this;
    }

    /**
     * @param bool $realtime
     * @return $this
     * @throws \Exception
     */
    public function setRealtime($realtime)
    {
        $this->verifyNotLocked();
        $this->realtime = (bool)$realtime;
        return $this;
    }

    /**
     * @param $type
     * @return $this
     * @throws \Exception
     */
    public function setType($type)
    {
        $this->verifyNotLocked();
        $this->type = $type;
        return $this;
    }



    public function setDefaultHydrator($hydrator)
    {
        $this->verifyNotLocked();
        $this->defaultHydrator = $hydrator;
        return $this;
    }

    /**
     * @param array $options
     * @return SearchIndexConfigBuilder
     */
    public static function create(array $options)
    {
        $defaults = [
            'type' => 'lucene',
            'realtime' => false,
            'default_hydrator' => null,
        ];
        $options = array_merge($defaults, $options);
        $builder = new self();
        return $builder
            ->setType($options['type'])
            ->setRealtime($options['realtime'])
            ->setDefaultHydrator($options['default_hydrator'])
            ->setDriverOptions($options['driver_options'])
            ;
    }


    public function getConfig()
    {
        $config = clone $this;
        $this->locked = true;

        return $config;
    }
}