<?php
namespace Raw\Bundle\UserBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Raw\Bundle\UserBundle\Behaviour\OwnableInterface;
use Raw\Bundle\UserBundle\Entity\Notification;
use Raw\Bundle\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OwnableSubscriber implements EventSubscriber, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    protected function initDeps()
    {
        $this->tokenStorage = $this->container->get('security.token_storage');
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',// 'preUpdate'
        ];
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if(!$entity instanceof OwnableInterface) {
            return;
        }

        $this->initDeps();
        $this->updateOwner($entity);
    }

    private function updateOwner(OwnableInterface $entity)
    {

        if($entity->hasOwner()) {
            return;
        }

        $token = $this->tokenStorage->getToken();

        if($token === null) {
            return;
        }

        $owner = $token->getUser();

        if(!$owner instanceof User) {
            return;
        }

        $entity->setOwner($owner);
    }
}