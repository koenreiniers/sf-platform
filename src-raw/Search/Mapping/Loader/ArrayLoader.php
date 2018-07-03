<?php
namespace Raw\Search\Mapping\Loader;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use Raw\Search\Mapping\ClassMetadata;

use Raw\Search\Mapping;

class ArrayLoader implements LoaderInterface
{


    /**
     * @var array
     */
    private $mappings;

    public function __construct(array $mappings)
    {
        $this->mappings = $mappings;
    }

    /**
     * @param ClassMetadata $classMetadata
     *
     * @return bool - true on success, false on failure
     */
    public function loadClassMetadata(ClassMetadata $classMetadata)
    {
        $className = $classMetadata->getClassName();
        if(!isset($this->mappings[$className])) {
            return false;
        }
        $mapping = $this->mappings[$className];

        $classMetadata->mapResourceType($mapping['type']);

        $classMetadata->mapIndexes($mapping['indexes']);

        foreach($mapping['fields'] as $fieldName => $fieldMapping) {
            $classMetadata->mapField($fieldName, $fieldMapping);
        }

        $classMetadata->mapFilters($mapping['filters']);

        return true;
    }
}