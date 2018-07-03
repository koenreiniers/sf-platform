<?php
namespace Raw\Bundle\BatchBundle\MassAction;

use Doctrine\ORM\EntityManager;
use Raw\Component\Batch\Job\Launcher\SymfonyLauncher;
use Raw\Bundle\BatchBundle\Entity\JobInstance;
use Raw\Component\Grid\Extension\MassActions\MassAction;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BatchJobMassAction extends MassAction
{
    /**
     * @var SymfonyLauncher
     */
    private $jobLauncher;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * BatchJobMassAction constructor.
     * @param SymfonyLauncher $jobLauncher
     * @param EntityManager $entityManager
     */
    public function __construct(SymfonyLauncher $jobLauncher, EntityManager $entityManager)
    {
        $this->jobLauncher = $jobLauncher;
        $this->entityManager = $entityManager;
    }

    public function execute(array $ids, array $records, array $options)
    {
        $jobInstanceCode = $options['job_instance'];
        $jobParameters = $options['job_parameters'];

        $args = [];
        foreach($jobParameters as $target => $source) {
            $value = $source;
            if($value === ':ids') {
                $value = $ids;
            } else if($value === ':records') {
                $value = $records;
            }
            $args[$target] = $value;
        }
        $jobInstance = $this->entityManager->getRepository(JobInstance::class)->findOneBy(['code' => $jobInstanceCode]);

        $extraParams = $args;

        $background = $options['background'];
        $this->jobLauncher->launch($jobInstance, $extraParams, $background);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['job_instance']);
        $resolver->setDefaults([
            'background' => true,
            'job_parameters' => [],
        ]);
        $resolver->setAllowedTypes('background', ['boolean']);
        $resolver->setAllowedTypes('job_parameters', ['array']);
    }
}