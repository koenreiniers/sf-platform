<?php
namespace Raw\Component\Batch\Event;

use Raw\Component\Batch\JobExecution;
use Symfony\Component\EventDispatcher\Event;

class JobExecutionEvent extends Event
{
    /**
     * @var JobExecution
     */
    private $jobExecution;

    /**
     * JobExecutionEvent constructor.
     * @param JobExecution $jobExecution
     */
    public function __construct(JobExecution $jobExecution)
    {
        $this->jobExecution = $jobExecution;
    }

    /**
     * @return JobExecution
     */
    public function getJobExecution()
    {
        return $this->jobExecution;
    }


}