<?php
namespace Raw\Component\Batch\Job;

use Raw\Component\Batch\Job\Job;
use Symfony\Component\DependencyInjection\ContainerInterface;

interface JobLoaderInterface
{
    /**
     * @param string $name
     * 
     * @return Job|null
     */
    public function load($name);

    /**
     * @return string
     */
    public function getJobNames();
}