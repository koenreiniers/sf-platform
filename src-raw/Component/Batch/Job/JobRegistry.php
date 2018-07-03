<?php
namespace Raw\Component\Batch\Job;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class JobRegistry
{
    /**
     * @var JobInterface[]
     */
    private $loadedJobs = [];

    /**
     * @var JobLoaderInterface
     */
    private $loader;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * JobRegistry constructor.
     * @param JobLoaderInterface $loader
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(JobLoaderInterface $loader, EventDispatcherInterface $eventDispatcher)
    {
        $this->loader = $loader;

        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     * @param string $name
     *
     * @return boolean
     */
    public function hasJob($name)
    {
        if(isset($this->loadedJobs[$name])) {
            return true;
        }
        try {
            $this->getJob($name);
        } catch(\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @return string[]
     */
    public function getJobNames()
    {
        return $this->loader->getJobNames();
    }

    public function getJob($name)
    {
        if(isset($this->loadedJobs[$name])) {
            return $this->loadedJobs[$name];
        }
        $job = $this->loader->load($name);
        if($job === null) {
            throw new \Exception(sprintf('Job "%s" does not exist', $name));
        }
        return $this->loadedJobs[$name] = $job;
    }
}