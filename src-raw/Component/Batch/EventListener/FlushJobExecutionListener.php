<?php
namespace Raw\Component\Batch\EventListener;

use Doctrine\ORM\EntityManager;
use Raw\Component\Batch\Event\JobExecutionEvent;
use Raw\Component\Batch\JobExecution;

class FlushJobExecutionListener
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var null|int
     */
    private $lastFlush = null;

    /**
     * Flush interval in ms
     * @var int
     */
    private $flushInterval = 500;

    /**
     * FlushJobExecutionListener constructor.
     * @param EntityManager $entityManager
     * @param int $flushInterval
     */
    public function __construct(EntityManager $entityManager, $flushInterval = 500)
    {
        $this->entityManager = $entityManager;
        $this->flushInterval = $flushInterval;
    }

    public function onUpdate(JobExecutionEvent $event)
    {
        $this->tryFlush($event->getJobExecution());
    }

    private function tryFlush(JobExecution $jobExecution)
    {
        $currentMs = microtime(true) * 1000;

        if($this->lastFlush === null || $this->lastFlush + $this->flushInterval <= $currentMs) {
            $this->flush($jobExecution);
        }
    }

    protected function flush(JobExecution $jobExecution)
    {
        $this->entityManager->flush($jobExecution);
        $this->lastFlush = microtime(true) * 1000;
    }

}