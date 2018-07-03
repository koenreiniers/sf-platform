<?php
namespace Raw\Component\Batch\Job;

use Raw\Bundle\BatchBundle\Entity\JobExecution;
use Raw\Bundle\BatchBundle\Entity\JobInstance;

interface JobLauncherInterface
{
    /**
     * @param JobInstance $jobInstance
     * @param array $extraParams
     * @param bool $background
     * @return JobExecution
     */
    public function launch(JobInstance $jobInstance, array $extraParams = [], $background = true);
}