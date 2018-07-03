<?php
namespace Raw\Bundle\SearchBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Raw\Search\Mapping\ClassMetadata;
use Raw\Search\Mapping\ClassMetadataBuilder;
use Raw\Search\Mapping\ClassMetadataFactory;
use Raw\Search\Registry;
use Raw\Search\Resource\Type\OrmResourceType;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\VarDumper\VarDumper;
use ZendSearch\Lucene\Document;

class DoctrineUpdateIndexesSubscriber implements EventSubscriber
{
    use ContainerAwareTrait;

    public function getSubscribedEvents()
    {
        return ['postPersist', 'postUpdate', 'preRemove'];
    }


    /**
     * @var Registry
     */
    private $searchRegistry;

    /**
     * @var ClassMetadataFactory
     */
    private $classMetadataFactory;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

    }

    private function init()
    {
        $container = $this->container;

        $this->searchRegistry = $container->get('raw_search.registry');
        $this->classMetadataFactory = $container->get('raw_search.class_metadata_factory');
    }

    private function supports($entity)
    {
        $className = get_class($entity);
        return $this->classMetadataFactory->hasClassMetadata($className) && $this->classMetadataFactory->getClassMetadata($className)->getResourceType() === OrmResourceType::NAME;
    }

    public function preRemove(LifecycleEventArgs $event)
    {
        $this->init();

        $entity = $event->getEntity();
        if(!$this->supports($entity)) {
            return;
        }
        $classMetadata = $this->classMetadataFactory->getClassMetadata(get_class($entity));

        foreach($classMetadata->getIndexes() as $indexName) {
            $index = $this->searchRegistry->getIndex($indexName);
            $index->deleteResourceDocument($entity);
        }
    }

    public function postUpdate(LifecycleEventArgs $event)
    {
        $this->init();
        $entity = $event->getEntity();
        if(!$this->supports($entity)) {
            return;
        }
        $classMetadata = $this->classMetadataFactory->getClassMetadata(get_class($entity));

        foreach($classMetadata->getIndexes() as $indexName) {
            $index = $this->searchRegistry->getIndex($indexName);
            $index->updateResourceDocument($entity);
        }
    }


    public function postPersist(LifecycleEventArgs $event)
    {
        $this->init();

        $entity = $event->getEntity();

        if(!$this->supports($entity)) {
            return;
        }

        $classMetadata = $this->classMetadataFactory->getClassMetadata(get_class($entity));

        foreach($classMetadata->getIndexes() as $indexName) {
            $index = $this->searchRegistry->getIndex($indexName);
            $index->createResourceDocument($entity);
        }

    }
}