<?php
namespace Raw\Component\Batch;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Raw\Component\Batch\Model\Warning;

class StepExecution
{
    const STATUS_STARTING = 'starting';
    const STATUS_RUNNING = 'running';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var JobExecution
     */
    protected $jobExecution;

    /**
     * @var array
     */
    protected $summary = [];

    /**
     * @var string
     */
    protected $status = self::STATUS_STARTING;

    /**
     * @var string
     */
    protected $stepName;

    /**
     * @var \DateTime
     */
    protected $startedAt;

    /**
     * @var \DateTime
     */
    protected $completedAt;

    /**
     * @var Collection|Warning[]
     */
    protected $warnings;

    public function __construct()
    {
        $this->warnings = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param \DateTime $startedAt
     * @return StepExecution
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    /**
     * @param \DateTime $completedAt
     * @return StepExecution
     */
    public function setCompletedAt($completedAt)
    {
        $this->completedAt = $completedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getStepName()
    {
        return $this->stepName;
    }

    /**
     * @param string $stepName
     * @return StepExecution
     */
    public function setStepName($stepName)
    {
        $this->stepName = $stepName;
        return $this;
    }



    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function update()
    {
        $this->jobExecution->update();
    }

    /**
     * @param string $status
     * @return StepExecution
     */
    public function setStatus($status)
    {
        $this->status = $status;
        $this->update();
        return $this;
    }

    /**
     * @return JobExecution
     */
    public function getJobExecution()
    {
        return $this->jobExecution;
    }

    /**
     * @param JobExecution $jobExecution
     * @return StepExecution
     */
    public function setJobExecution($jobExecution)
    {
        $this->jobExecution = $jobExecution;
        return $this;
    }

    /**
     * @return array
     */
    public function getJobParameters()
    {
        return $this->jobExecution->getJobParameters();
    }

    /**
     * @param string $key
     * @param int $delta
     * @return $this
     */
    public function incrementSummaryInfo($key, $delta = 1)
    {
        $value = $this->getSummaryInfo($key, 0) + $delta;
        $this->setSummaryInfo($key, $value);
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setSummaryInfo($key, $value)
    {
        $this->summary[$key] = $value;
        $this->update();
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return null
     */
    public function getSummaryInfo($key, $default = null)
    {
        if(!isset($this->summary[$key])) {
            return $default;
        }
        return $this->summary[$key];
    }

    /**
     * @return array
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param array $summary
     * @return StepExecution
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        $this->update();
        return $this;
    }

    public function getWarnings()
    {
        return $this->warnings;
    }

    public function addWarning(Warning $warning)
    {
        $this->warnings[] = $warning;
        $this->update();
        return $this;
    }

    public function removeWarning(Warning $warning)
    {
        $this->warnings->removeElement($warning);
        return $this;
    }
}