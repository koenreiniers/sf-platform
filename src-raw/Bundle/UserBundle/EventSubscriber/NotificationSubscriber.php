<?php
namespace Raw\Bundle\UserBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Raw\Bundle\UserBundle\Entity\Notification;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NotificationSubscriber implements EventSubscriber, ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected function initDeps()
    {

    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',// 'preUpdate'
        ];
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $notification = $event->getEntity();
        if(!$notification instanceof Notification) {
            return;
        }

        $this->initDeps();
    }

}