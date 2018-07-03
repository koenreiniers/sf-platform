<?php
namespace Raw\Component\Batch\Job\Loader;

use Raw\Component\Batch\Job\Job;
use Raw\Component\Batch\Job\JobLoaderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerLoader implements JobLoaderInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $idMap;

    /**
     * ContainerLoader constructor.
     * @param ContainerInterface $container
     * @param array $idMap
     */
    public function __construct(ContainerInterface $container, array $idMap)
    {
        $this->container = $container;
        $this->idMap = $idMap;
    }

    /**
     * @inheritdoc
     */
    public function getJobNames()
    {
        return array_keys($this->idMap);
    }

    /**
     * @param string $name
     *
     * @return Job
     */
    public function load($name)
    {
        if(!isset($this->idMap[$name])) {
            return null;
        }
        $id = $this->idMap[$name];
        if(!$this->container->has($id)) {
            return null;
        }
        return $this->container->get($id);
    }
}