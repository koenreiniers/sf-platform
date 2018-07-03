<?php
namespace Raw\Bundle\UserBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Raw\Component\Batch\BatchEvents;
use Raw\Component\Batch\Event\JobExecutionEvent;
use Raw\Component\Batch\JobExecution;
use Raw\Bundle\UserBundle\Entity\Notification;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class JobNotifySubscriber implements EventSubscriberInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var RouterInterface
     */
    private $router;

    protected function initDeps()
    {
        $this->entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $this->router = $this->container->get('router');
    }

    public static function getSubscribedEvents()
    {
        return [
            BatchEvents::JOB_ENDED => 'onJobEnded',
        ];
    }

    public function onJobEnded(JobExecutionEvent $event)
    {
        $this->initDeps();

        $jobExecution = $event->getJobExecution();

        $type = 'success';
        $message = '';
        switch($jobExecution->getStatus()) {
            case JobExecution::STATUS_COMPLETED:
                $type = 'success';
                $message = sprintf('Job "%s" was successfully completed', $jobExecution->getJobInstance()->getCode());
                break;
            case JobExecution::STATUS_FAILED:
                $type = 'warning';
                $message = sprintf('Job "%s" has failed to complete', $jobExecution->getJobInstance()->getCode());
                break;
        }
        $owner = $jobExecution->getOwner();

        $url = $this->router->generate('raw_batch.job_execution.view', [
            'id' => $jobExecution->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $notification = $this->createNotification()
            ->setMessage($message)
            ->setType($type)
            ->setOwner($owner)
            ->setUrl($url);
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }

    public function createNotification()
    {
        $notification = new Notification();
        return $notification;
    }


}