<?php
namespace Raw\Bundle\VersioningBundle\EventSubscriber;

use Doctrine\ORM\EntityManager;
use Raw\Bundle\VersioningBundle\Behaviour\VersionableInterface;
use Raw\Bundle\VersioningBundle\Versioning\VersionFactory;
use Raw\Component\Admin\AdminEvents;
use Raw\Component\Admin\Event\ResourceEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminSaveSubscriber implements EventSubscriberInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var VersionFactory
     */
    private $versionFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    private function initDeps()
    {
        $this->versionFactory = $this->container->get('raw_versioning.factory.version');
        $this->entityManager = $this->container->get('doctrine.orm.default_entity_manager');
    }

    public static function getSubscribedEvents()
    {
        return [
            AdminEvents::POST_SAVE => 'postSave',
        ];
    }

    public function postSave(ResourceEvent $event)
    {
        $this->initDeps();
        $entity = $event->getResource();
        if(!$entity instanceof VersionableInterface) {
            return;
        }

        $version = $this->versionFactory->create($entity);

        $this->entityManager->persist($version);
        $this->entityManager->flush();
    }
}