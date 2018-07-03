<?php
namespace Raw\Bundle\VersioningBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Raw\Bundle\VersioningBundle\Behaviour\VersionableInterface;
use Raw\Bundle\VersioningBundle\Entity\Version;
use Raw\Bundle\VersioningBundle\Versioning\VersionFactory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Serializer;

class VersionableSubscriber implements EventSubscriber, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    private $pendingVersions = [];
    /**
     * @var VersionFactory
     */
    private $versionFactory;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    private function initDeps()
    {
        $this->versionFactory = $this->container->get('raw_versioning.factory.version');
        $this->tokenStorage = $this->container->get('security.token_storage');
    }

    public function getSubscribedEvents()
    {
        return [];
        return [
            'postPersist', 'postUpdate', 'postFlush',
        ];
    }

    public function postPersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if(!$entity instanceof VersionableInterface) {
            return;
        }
        $this->initDeps();

        $this->pendingVersions[] = $this->createVersion($entity);
    }

    private function createVersion(VersionableInterface $resource)
    {
        $version = $this->versionFactory->create($resource);
        $version->setAuthor($this->tokenStorage->getToken()->getUsername());
        return $version;
    }

    public function postUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if(!$entity instanceof VersionableInterface) {
            return;
        }
        $this->initDeps();

        $this->pendingVersions[] = $this->createVersion($entity);
    }

    public function postFlush(PostFlushEventArgs $event)
    {
        if(count($this->pendingVersions) === 0) {
            return;
        }
        $em = $event->getEntityManager();
        foreach($this->pendingVersions as $version) {
            $em->persist($version);
        }
        $this->pendingVersions = [];
        $em->flush();
    }
}