<?php
namespace Raw\Component\Batch\Job;

use Doctrine\ORM\EntityManager;
use Raw\Bundle\BatchBundle\Entity\StepExecution;
use Raw\Component\Batch\BatchEvents;
use Raw\Component\Batch\Event\JobExecutionEvent;
use Raw\Component\Batch\JobExecution;
use Raw\Component\Batch\StepInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class JobExecutor
{
    /**
     * @var JobRegistry
     */
    private $jobRegistry;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * JobExecutor constructor.
     * @param JobRegistry $jobRegistry
     * @param EventDispatcherInterface $eventDispatcher
     * @param EntityManager $entityManager
     */
    public function __construct(JobRegistry $jobRegistry, EventDispatcherInterface $eventDispatcher, EntityManager $entityManager)
    {
        $this->jobRegistry = $jobRegistry;
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
    }

    private function executeStep(JobExecution $jobExecution, StepInterface $step)
    {
        $stepExecution = $this->createStepExecution($jobExecution, $step);
        $stepExecution->setStatus(StepExecution::STATUS_RUNNING);
        $stepExecution->setStartedAt(new \DateTime());
        try {
            $step->execute($stepExecution);
            $stepExecution->setStatus(StepExecution::STATUS_COMPLETED);
        } catch(\Exception $e) {
            $stepExecution->setStatus(StepExecution::STATUS_FAILED);
            throw $e;
        } finally {
            $stepExecution->setCompletedAt(new \DateTime());
        }
    }

    public function execute(JobExecution $jobExecution)
    {
        $job = $this->jobRegistry->getJob($jobExecution->getJobInstance()->getJobName());
        try {
            $jobExecution->setStatus(JobExecution::STATUS_RUNNING);
            $jobExecution->setEventDispatcher($this->eventDispatcher);


            foreach($job->getSteps() as $step) {
                $this->executeStep($jobExecution, $step);
            }

            $jobExecution->setStatus(JobExecution::STATUS_COMPLETED);
        } catch(\Exception $e) {
            $jobExecution->setStatus(JobExecution::STATUS_FAILED);
            $this->logException($jobExecution, $e);
            throw $e;
        } finally {
            $jobExecution->setEndedAt(new \DateTime());

            $this->entityManager->persist($jobExecution);
            $this->entityManager->flush($jobExecution);

            $this->eventDispatcher->dispatch(BatchEvents::JOB_ENDED, new JobExecutionEvent($jobExecution));
        }


    }

    private function logException(JobExecution $jobExecution, \Exception $e)
    {
        $failureExceptions = [];
        do {
            $failureExceptions[] = $this->normalizeException($e);
        } while(($e = $e->getPrevious()) !== null);

        $logsDir = dirname($jobExecution->getLogPath());

        if(!is_dir($logsDir)) {
            mkdir($logsDir, 0755, true);
        }
        try {
            $content = json_encode($failureExceptions);
        } catch(\Exception $e) {
            $content = $e->getMessage();
        }

        file_put_contents($jobExecution->getLogPath(), $content);
    }

    private function normalizeException(\Exception $e)
    {
        return [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
        ];
    }

    private function createStepExecution(JobExecution $jobExecution, StepInterface $step)
    {
        $stepExecution = new StepExecution();
        $stepExecution->setJobExecution($jobExecution);
        $stepExecution->setStatus(StepExecution::STATUS_STARTING);
        $stepExecution->setStepName($step->getName());
        $jobExecution->addStepExecution($stepExecution);
        return $stepExecution;
    }
}