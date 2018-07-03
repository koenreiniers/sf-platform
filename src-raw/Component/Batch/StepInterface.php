<?php
namespace Raw\Component\Batch;

interface StepInterface
{
    /**
     * @param StepExecution $stepExecution
     */
    public function execute(StepExecution $stepExecution);

    /**
     * @return string
     */
    public function getName();
}