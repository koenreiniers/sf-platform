<?php
namespace Raw\Component\Batch\Job;

use Raw\Component\Batch\StepInterface;
use Symfony\Component\Form\FormBuilderInterface;

interface JobInterface
{
    /**
     * @return StepInterface[]
     */
    public function getSteps();

    public function buildForm(FormBuilderInterface $builder);
}