<?php
namespace Raw\Component\Batch;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Raw\Component\Batch\Event\JobExecutionEvent;
use Raw\Component\Batch\Job\JobInstance;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Raw\Bundle\UserBundle\Behaviour\OwnableInterface;
use Raw\Bundle\UserBundle\Behaviour\OwnableTrait;

class JobExecution implements OwnableInterface
{
    use OwnableTrait;

    const STATUS_STARTING = 'starting';
    const STATUS_RUNNING = 'running';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @return JobExecution
     */
    public function setEventDispatcher($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        return $this;
    }

    private function dispatch($eventName, $event)
    {

        if($this->eventDispatcher === null) {
            return;
        }
        $this->eventDispatcher->dispatch($eventName, $event);
    }

    public function update()
    {
        $this->dispatch(BatchEvents::UPDATE, new JobExecutionEvent($this));
    }

    /**
     * @var int
     */
    protected $id;

    /**
     * @var JobInstance
     */
    protected $jobInstance;

    /**
     * @var array
     */
    protected $jobParameters;

    /**
     * @var StepExecution[]|Collection
     */
    protected $stepExecutions;

    /**
     * @var string
     */
    protected $status = self::STATUS_STARTING;

    /**
     * @var \DateTime
     */
    protected $startedAt;

    /**
     * @var \DateTime|null
     */
    protected $endedAt;

    /**
     * @var string
     */
    protected $logPath;

    public function __construct()
    {
        $this->stepExecutions = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getLogPath()
    {
        return $this->logPath;
    }

    /**
     * @param string $logPath
     * @return JobExecution
     */
    public function setLogPath($logPath)
    {
        $this->logPath = $logPath;
        return $this;
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
     * @return JobExecution
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndedAt()
    {
        return $this->endedAt;
    }

    /**
     * @param \DateTime|null $endedAt
     * @return JobExecution
     */
    public function setEndedAt($endedAt)
    {
        $this->endedAt = $endedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return JobExecution
     */
    public function setStatus($status)
    {
        $this->status = $status;
        $this->update();
        return $this;
    }

    /**
     * @return JobInstance
     */
    public function getJobInstance()
    {
        return $this->jobInstance;
    }

    /**
     * @param JobInstance $jobInstance
     * @return JobExecution
     */
    public function setJobInstance($jobInstance)
    {
        $this->jobInstance = $jobInstance;
        return $this;
    }

    /**
     * @return array
     */
    public function getJobParameters()
    {
        return $this->jobParameters;
    }

    /**
     * @param array $jobParameters
     * @return JobExecution
     */
    public function setJobParameters($jobParameters)
    {
        $this->jobParameters = $jobParameters;
        return $this;
    }

    /**
     * @return Collection|StepExecution[]
     */
    public function getStepExecutions()
    {
        return $this->stepExecutions;
    }

    /**
     * @param StepExecution $stepExecution
     * @return $this
     */
    public function addStepExecution(StepExecution $stepExecution)
    {
        $this->stepExecutions[] = $stepExecution;
        return $this;
    }

    /**
     * @param StepExecution $stepExecution
     * @return $this
     */
    public function removeStepExecution(StepExecution $stepExecution)
    {
        $this->stepExecutions->removeElement($stepExecution);
        return $this;
    }

}