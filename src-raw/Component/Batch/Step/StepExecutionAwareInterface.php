<?php
namespace Raw\Component\Batch\Step;

use Raw\Component\Batch\StepExecution;

interface StepExecutionAwareInterface
{
    /**
     * @param StepExecution $stepExecution
     */
    public function setStepExecution(StepExecution $stepExecution);
}