<?php
namespace Raw\Component\Batch\Job;

use Cron\CronExpression;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Raw\Component\Batch\JobExecution;

class JobInstance
{
    public function __toString()
    {
        if($this->id === null) {
            return 'New job instance';
        }
        return $this->code;
    }

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $jobName;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var JobExecution[]|Collection
     */
    protected $jobExecutions;

    /**
     * @var string|null
     */
    protected $cronExpression;

    /**
     * @var bool
     */
    protected $cronEnabled = false;

    /**
     * @var \DateTime|null
     */
    protected $cronNextRunAt;

    public function __construct()
    {
        $this->jobExecutions = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return JobInstance
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getJobName()
    {
        return $this->jobName;
    }

    /**
     * @param string $jobName
     * @return JobInstance
     */
    public function setJobName($jobName)
    {
        $this->jobName = $jobName;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return JobInstance
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return \Raw\Component\Batch\JobExecution[]
     */
    public function getJobExecutions()
    {
        return $this->jobExecutions;
    }

    /**
     * @param JobExecution $jobExecution
     * @return $this
     */
    public function addJobExecution(JobExecution $jobExecution)
    {
        $this->jobExecutions[] = $jobExecution;
        return $this;
    }

    /**
     * @param JobExecution $jobExecution
     * @return $this
     */
    public function removeJobExecution(JobExecution $jobExecution)
    {
        $this->jobExecutions->removeElement($jobExecution);
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCronExpression()
    {
        return $this->cronExpression;
    }

    /**
     * @param null|string $cronExpression
     * @return JobInstance
     */
    public function setCronExpression($cronExpression)
    {
        $this->cronExpression = $cronExpression;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isCronEnabled()
    {
        return $this->cronEnabled;
    }

    /**
     * @param boolean $cronEnabled
     * @return JobInstance
     */
    public function setCronEnabled($cronEnabled)
    {
        $this->cronEnabled = $cronEnabled;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCronNextRunAt()
    {
        return $this->cronNextRunAt;
    }

    public function updateCronRunDate()
    {
        $exprString = $this->getCronExpression();

        $expr = CronExpression::factory($exprString);

        $nextRunAt = $expr->getNextRunDate();
        $this->setCronNextRunAt($nextRunAt);
    }

    /**
     * @param \DateTime|null $cronNextRunAt
     * @return JobInstance
     */
    public function setCronNextRunAt($cronNextRunAt)
    {
        $this->cronNextRunAt = $cronNextRunAt;
        return $this;
    }
}