<?php
namespace Raw\Component\Batch\Job\Launcher;

use Doctrine\ORM\EntityManager;
use Raw\Component\Batch\BatchEvents;
use Raw\Component\Batch\Event\JobExecutionEvent;
use Raw\Component\Batch\Job\JobExecutor;
use Raw\Component\Batch\Job\JobLauncherInterface;
use Raw\Component\Batch\Job\JobRegistry;
use Raw\Bundle\BatchBundle\Entity\JobExecution;
use Raw\Bundle\BatchBundle\Entity\JobInstance;
use Raw\Component\Console\CommandExecutor;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\VarDumper\VarDumper;

class SymfonyLauncher implements JobLauncherInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var JobRegistry
     */
    private $jobRegistry;

    /**
     * @var CommandExecutor
     */
    private $commandExecutor;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var JobExecutor
     */
    private $jobExecutor;

    /**
     * @var string
     */
    private $logsDir;

    public function __construct(CommandExecutor $commandExecutor)
    {
        global $kernel;
        $this->commandExecutor = $commandExecutor;
        $container = $kernel->getContainer();
        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
        $this->jobRegistry = $container->get('raw_batch.job_registry');
        $this->eventDispatcher = $container->get('event_dispatcher');
        $this->jobExecutor = $container->get('raw_batch.job_executor');
        $this->logsDir = $container->getParameter('raw_batch.logs_dir');
    }



    /**
     * @param JobInstance $jobInstance
     * @param array $extraParams
     * @param bool $background
     * @return JobExecution
     * @throws \Exception
     */
    public function launch(JobInstance $jobInstance, array $extraParams = [], $background = true)
    {

        $jobExecution = $this->createJobExecution($jobInstance);

        $params = array_merge($jobInstance->getParameters(), $extraParams);

        if($background) {

            $cmd = sprintf('raw:batch:execute %s %s --params=%s', $jobInstance->getCode(), $jobExecution->getId(), escapeshellarg(json_encode($params, JSON_HEX_APOS)));



            $this->commandExecutor->executeBackground($cmd);

        } else {

            $jobExecution->setJobParameters($params);
            $this->jobExecutor->execute($jobExecution);

        }

        return $jobExecution;
    }

    private function createJobExecution(JobInstance $jobInstance)
    {
        $jobExecution = new JobExecution();
        $jobExecution->setJobInstance($jobInstance);
        $jobInstance->addJobExecution($jobExecution);
        $jobExecution->setStatus(JobExecution::STATUS_STARTING);
        $this->entityManager->persist($jobExecution);
        $this->entityManager->flush($jobInstance);

        $logPath = $this->logsDir.'/'.$jobExecution->getId().'.log';
        $jobExecution->setLogPath($logPath);

        $this->entityManager->flush();

        return $jobExecution;
    }
}