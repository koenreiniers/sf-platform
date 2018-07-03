<?php
namespace Raw\Component\Batch;

class BatchEvents
{
    const UPDATE = 'raw_batch.update';

    const JOB_FAILED = 'raw_batch.job_failed';
    const JOB_COMPLETED = 'raw_batch.job_completed';

    /**
     * Receives an instance of Raw\Component\Batch\Event\JobExecutionEvent
     */
    const JOB_ENDED = 'raw_batch.job_ended';
}