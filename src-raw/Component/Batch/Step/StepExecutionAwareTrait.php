<?php
namespace Raw\Component\Batch\Step;

use Raw\Component\Batch\StepExecution;

trait StepExecutionAwareTrait
{
    /**
     * @var StepExecution
     */
    protected $stepExecution;

    /**
     * @param StepExecution $stepExecution
     */
    public function setStepExecution(StepExecution $stepExecution)
    {
        $this->stepExecution = $stepExecution;
    }
}