<?php
namespace Raw\Bundle\BatchBundle\Command;

use Raw\Component\Batch\BatchEvents;
use Raw\Component\Batch\Event\JobExecutionEvent;
use Raw\Bundle\BatchBundle\Entity\JobExecution;
use Raw\Bundle\BatchBundle\Entity\JobInstance;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper\VarDumper;

abstract class AbstractBatchCommand extends ContainerAwareCommand
{

    protected function launch(JobInstance $jobInstance, array $extraParams = [], $executionId = null)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $jobRegistry = $this->getContainer()->get('raw_batch.job_registry');
        $executor = $this->getContainer()->get('raw_batch.job_executor');

        $job = $jobRegistry->getJob($jobInstance->getJobName());

        if($executionId !== null) {
            $jobExecution = $em->getRepository(JobExecution::class)->find($executionId);
        } else {
            $jobExecution = $this->createJobExecution($jobInstance);
        }


        $params = array_merge($jobInstance->getParameters(), $extraParams);
        $jobExecution->setJobParameters($params);
        $jobExecution->setStartedAt(new \DateTime());

        $this->update($jobExecution);

        $executor->execute($jobExecution);
    }


    private function update($entity)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $em->persist($entity);
        $em->flush($entity);
    }

    private function createJobExecution(JobInstance $jobInstance)
    {
        $logsDir = $this->getContainer()->getParameter('raw_batch.logs_dir');
        $jobExecution = new JobExecution();
        $jobExecution->setJobInstance($jobInstance);
        $jobInstance->addJobExecution($jobExecution);

        $this->update($jobExecution);
        $logPath = $logsDir.'/'.$jobExecution->getId().'.log';
        $jobExecution->setLogPath($logPath);
        $this->update($jobExecution);
        return $jobExecution;
    }
}