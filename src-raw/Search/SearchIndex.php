<?php
namespace Raw\Search;

use Raw\Bundle\LuceneBundle\Lucene\Expression\Expression;
use Raw\Search\Document\DocumentFactory;
use Raw\Search\Driver\LuceneDriver;
use Raw\Search\Driver\TraceableDriver;
use Raw\Search\Profiler\Profiler;
use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Index;
use Raw\Search\Query\QueryHit;

class SearchIndex
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var SearchIndexConfig
     */
    private $config;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var LuceneDriver
     */
    private $driver;

    /**
     * @var Profiler
     */
    private $profiler;

    public function __construct($name, SearchIndexConfig $config)
    {
        $this->name = $name;
        $this->config = $config;
        $this->registry = $config->getRegistry();
        $this->driver = new TraceableDriver($config->getDriver());
        $this->profiler = new Profiler($this);
    }

    public function getStopwatch()
    {
        return $this->driver->getStopwatch();
    }

    public function addDocument(Document $document)
    {
        $this->driver->addDocument($this, $document);
        return $this;
    }

    /**
     * @param object $resource
     * @return SearchIndex
     */
    public function updateResourceDocument($resource)
    {
        $this->deleteResourceDocument($resource);
        return $this->createResourceDocument($resource);
    }

    /**
     * @param object $resource
     * @return array
     */
    public function getResourceFieldMappings($resource)
    {
        return $this->registry->getClassMetadata(get_class($resource))->getFieldMappingsByIndex($this);
    }

    /**
     * @param object $resource
     * @return $this
     */
    public function createResourceDocument($resource)
    {
        $classMetadata = $this->registry->getClassMetadata(get_class($resource));
        $document = $this->registry->getDocumentFactory()->createResourceDocument($classMetadata, $resource, $this->getResourceFieldMappings($resource));
        $this->addDocument($document);
        return $this;
    }

    /**
     * @param object $resource
     * @return $this
     */
    public function deleteResourceDocument($resource)
    {
        $documentId = $this->getDocumentId($resource);
        if($documentId !== null) {
            $this->delete($documentId);
        }
        return $this;
    }

    public function getDocument($id)
    {
        return $this->driver->getDocument($this, $id);
    }

    public function getDocumentId($resource)
    {
        $classMetadata = $this->config->getRegistry()->getClassMetadata($resource);

        $resourceName = $classMetadata->getResourceName();
        $resourceId = $classMetadata->getResourceId($resource);

        $query = sprintf('%s: "%s" AND %s: "%s"', DocumentFactory::FIELD_RESOURCE_NAME, $resourceName, DocumentFactory::FIELD_RESOURCE_ID, $resourceId);
        $hits = $this->find($query);

        if(count($hits) === 0) {
            return null;
        }
        /** @var QueryHit $hit */
        $hit = $hits[0];

        return $hit->getDocumentId();
    }

    public function delete($id)
    {
        $this->driver->delete($this, $id);
    }

    public function find($query)
    {
        if($query instanceof Expression) {
            $query = (string)$query;
        }
        return $this->driver->find($this, $query);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return SearchIndexConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function erase()
    {
        $this->driver->eraseIndex($this);
    }


    public function optimize()
    {
        $this->driver->optimizeIndex($this);
    }
}