<?php
namespace Raw\Component\Batch\Job;

use Raw\Component\Batch\JobExecution;
use Raw\Bundle\BatchBundle\Entity\StepExecution;
use Raw\Component\Batch\StepInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormBuilderInterface;

class Job implements JobInterface
{
    /**
     * @var StepInterface[]
     */
    private $steps;

    /**
     * Job constructor.
     * @param \Raw\Component\Batch\StepInterface[] $steps
     */
    public function __construct(array $steps)
    {
        $this->steps = $steps;
    }

    /**
     * @return \Raw\Component\Batch\StepInterface[]
     */
    public function getSteps()
    {
        return $this->steps;
    }

    public function buildForm(FormBuilderInterface $builder)
    {

    }
}