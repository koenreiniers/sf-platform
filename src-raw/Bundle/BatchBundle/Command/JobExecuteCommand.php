<?php
namespace Raw\Bundle\BatchBundle\Command;

use Raw\Bundle\BatchBundle\Entity\JobInstance;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class JobExecuteCommand extends AbstractBatchCommand
{
    protected function configure()
    {
        $this->setName('raw:batch:execute');
        $this->addArgument('code', InputArgument::REQUIRED);
        $this->addArgument('execution', InputArgument::OPTIONAL);
        $this->addOption('params', 'p', InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $code = $input->getArgument('code');
        $jobInstance = $em->getRepository(JobInstance::class)->findOneBy(['code' => $code]);

        if($jobInstance === null) {
            throw new \Exception(sprintf('No job instance found with code "%s"', $code));
        }

        $extraParams = [];
        if($input->hasOption('params')) {
            $extraParams = json_decode($input->getOption('params'), true);
        }

        $executionId = $input->hasArgument('execution') ? $input->getArgument('execution') : null;

        $this->launch($jobInstance, $extraParams, $executionId);
    }
}