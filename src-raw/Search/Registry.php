<?php
namespace Raw\Search;

use Raw\Search\Document\DocumentFactory;
use Raw\Search\Driver\LuceneDriver;
use Raw\Search\Index\IndexConfigProvider;
use Raw\Search\Mapping\ClassMetadata;
use Raw\Search\Mapping\ClassMetadataFactory;
use Raw\Search\Populator\PopulatorInterface;
use Raw\Search\Repository\SearchRepositoryFactory;
use Raw\Search\Resource\Type\OrmResourceType;
use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Index;

class Registry
{

    /**
     * @var Repository[]
     */
    private $repositories = [];

    /**
     * @var PopulatorInterface[]
     */
    private $populators = [];

    /**
     * @var ClassMetadataFactory
     */
    private $classMetadataFactory;

    /**
     * @var DocumentFactory
     */
    private $documentFactory;

    /**
     * @var SearchRepositoryFactory
     */
    private $repositoryFactory;

    /**
     * @var IndexRegistry
     */
    private $indexRegistry;

    /**
     * @var ResourceRegistry
     */
    private $resourceRegistry;

    private $drivers;


    public function __construct(array $configs, array $populators = [], ClassMetadataFactory $classMetadataFactory)
    {
        global $kernel; // TODO
        $container = $kernel->getContainer();
        $em = $container->get('doctrine.orm.default_entity_manager');
        $this->populators = $populators;
        $this->classMetadataFactory = $classMetadataFactory;
        $this->documentFactory = new DocumentFactory();
        $this->repositoryFactory = new SearchRepositoryFactory($container);
        $this->indexRegistry = new IndexRegistry(new IndexConfigProvider($this, $configs));
        $this->resourceRegistry = new ResourceRegistry([
            'orm' => new OrmResourceType($em),
        ]);
        $this->drivers = [
            'lucene' => new LuceneDriver(),
        ];
    }

    /**
     * @param string $name
     * @return SearchDriverInterface
     */
    public function getDriver($name)
    {
        return $this->drivers[$name];
    }

    /**
     * @param string $className
     * @return ClassMetadata|Mapping\ClassMetadataBuilder
     * @throws \Exception
     */
    public function getClassMetadata($className)
    {
        if(is_object($className)) {
            $className = get_class($className);
        }
        return $this->classMetadataFactory->getClassMetadata($className);
    }

    /**
     * @param string $name
     * @return Resource\ResourceTypeInterface
     * @throws \Exception
     */
    public function getResourceType($name)
    {
        return $this->resourceRegistry->getType($name);
    }

    public function populate(SearchIndex $index)
    {
        $metadata = $this->classMetadataFactory->getAllMetadata();
        foreach($metadata as $classMetadata) {
            $resources = $this->getResourceType($classMetadata->getResourceType())->findBy($classMetadata, $classMetadata->getFilterExpressions($index));
            foreach($resources as $resource) {
                $index->createResourceDocument($resource);
            }
        }
    }

    public function getDocumentFactory()
    {
        return $this->documentFactory;
    }

    /**
     * @param string $name
     * @return SearchIndex
     */
    public function getIndex($name)
    {
        return $this->indexRegistry->getIndex($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasIndex($name)
    {
        return $this->indexRegistry->hasIndex($name);
    }

    public function getPopulator($identifier)
    {
        if(!isset($this->populators[$identifier])) {
            throw new \Exception(sprintf('No populator found for index "%s"', $identifier));
        }
        return $this->populators[$identifier];
    }

    /**
     * @param string $name
     * @return Repository
     * @throws \Exception
     */
    public function getRepository($name)
    {
        if(isset($this->repositories[$name])) {
            return $this->repositories[$name];
        }
        return $this->repositories[$name] = $this->repositoryFactory->create($this->getIndex($name));
    }
}