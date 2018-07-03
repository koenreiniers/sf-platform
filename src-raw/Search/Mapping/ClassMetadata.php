<?php
namespace Raw\Search\Mapping;

use Doctrine\Common\Collections\ExpressionBuilder;
use Raw\Search\SearchIndex;
use Symfony\Component\VarDumper\VarDumper;

class ClassMetadata
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var string[]
     */
    protected $indexes = [];

    /**
     * @var array
     */
    protected $fieldNamesByIndex = [];


    /**
     * @var \ReflectionClass
     */
    protected $reflectionClass;

    /**
     * @var \ReflectionProperty[]
     */
    protected $reflectionProperties = [];

    /**
     * @var array
     */
    protected $fieldMappings = [];

    /**
     * @var string
     */
    protected $resourceType;

    /**
     * @var string
     */
    protected $resourceName;

    /**
     * @var array
     */
    protected $filterMappings;

    public function __construct($className)
    {
        $this->className = $className;
    }

    public function getFilterMappings()
    {
        return $this->filterMappings;
    }

    /**
     * @return string
     */
    public function getResourceType()
    {
        return $this->resourceType;
    }

    /**
     * @return string
     */
    public function getResourceName()
    {
        if($this->resourceName === null) {
            $resourceName = $this->getClassName();
            $resourceName =  str_replace('\\', '', $resourceName);
            $this->resourceName = $resourceName;
        }
        return $this->resourceName;
    }

    public function getResourceId($object)
    {
        $idValues = $this->getIdentifierValues($object);
        $resourceId = '';
        foreach($idValues as $key => $value) {
            $resourceId .= $key.':'.$value;
        }
        return $resourceId;
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass()
    {
        if($this->reflectionClass === null) {
            $this->reflectionClass = new \ReflectionClass($this->className);
        }
        return $this->reflectionClass;
    }

    public function getIdentifierValues($object)
    {
        return ['id' => $object->getId()]; // TODO
    }


    /**
     * @param string $propertyName
     *
     * @return \ReflectionProperty
     */
    public function getReflectionProperty($propertyName)
    {
        if(!isset($this->reflectionProperties[$propertyName])) {
            $this->reflectionProperties[$propertyName] = $this->getReflectionClass()->getProperty($propertyName);
        }
        return $this->reflectionProperties[$propertyName];
    }

    public function getPropertyValue($object, $propertyName)
    {
        $rp = $this->getReflectionProperty($propertyName);
        $rp->setAccessible(true);
        return $rp->getValue($object);
    }

    public function getFieldMappings()
    {
        return $this->fieldMappings;
    }

    public function getFilterMappingsByIndex($index)
    {
        if($index instanceof SearchIndex) {
            $index = $index->getName();
        }
        $mappings = [];
        foreach($this->filterMappings as $filterMapping) {
            if(in_array($index, $filterMapping['indexes'])) {
                $mappings[] = $filterMapping;
            }
        }
        return $mappings;
    }

    /**
     * @param $index
     * @return string[]
     */
    public function getFilterExpressions($index)
    {
        $filterMappings = $this->getFilterMappingsByIndex($index);
        $expressions = [];
        foreach($filterMappings as $filterMapping) {
            $expressions[] = $filterMapping['expr'];
        }
        return $expressions;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    public function getIndexes()
    {
        return $this->indexes;
    }

    public function getFieldNamesByIndex($index)
    {
        return $this->fieldNamesByIndex[$index];
    }

    public function getFieldMappingsByIndex($index)
    {
        if($index instanceof SearchIndex) {
            $index = $index->getName();
        }
        if(!in_array($index, $this->indexes)) {
            return [];
        }
        $mappings = [];
        foreach($this->fieldNamesByIndex[$index] as $fieldName) {
            $mappings[$fieldName] = $this->fieldMappings[$fieldName];
        }
        return $mappings;
    }
}