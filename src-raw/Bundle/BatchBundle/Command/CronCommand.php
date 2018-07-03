<?php
namespace Raw\Bundle\BatchBundle\Command;

use Raw\Bundle\BatchBundle\Entity\JobInstance;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronCommand extends AbstractBatchCommand
{
    protected function configure()
    {
        $this->setName('raw:batch:cron');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        /** @var JobInstance[] $jobInstances */
        $jobInstances = $em->getRepository(JobInstance::class)->findOverdueForCron();

        foreach($jobInstances as $jobInstance) {
            $output->writeln(sprintf('Launching job instance "%s"', $jobInstance->getCode()));
            $this->launch($jobInstance);
            $jobInstance->updateCronRunDate();
            $em->flush();
        }


    }
}