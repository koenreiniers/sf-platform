<?php
namespace Raw\Search\Mapping;



use Raw\Search\SearchIndex;
use Symfony\Component\VarDumper\VarDumper;

class ClassMetadataBuilder extends ClassMetadata
{


    /**
     * @var bool
     */
    private $compiled = false;

    public function mapField($name, array $mapping)
    {
        $this->verifyNotCompiled();

        $mapping = $this->resolveFieldMapping($name, $mapping);
        $this->fieldMappings[$name] = $mapping;
        return $this;
    }

    /**
     * @param string $resourceType
     * @return $this
     * @throws \Exception
     */
    public function mapResourceType($resourceType)
    {
        $this->verifyNotCompiled();
        $this->resourceType = $resourceType;
        return $this;
    }

    public function mapFilters(array $filterMappings)
    {
        $this->verifyNotCompiled();
        $this->filterMappings = $filterMappings;
        return $this;
    }

    private function resolveFieldMapping($property, array $mapping)
    {
        $defaults = [
            'type' => null,
            'indexes' => null,
            'name' => null,
            'encoding' => 'UTF-8',
        ];
        $mapping = array_merge($defaults, $mapping);
        if($mapping['name'] === null) {
            $mapping['name'] = $property;
        }
        #$mapping['property'] = $property;
        if(!$this->validateFieldMapping($mapping)) {
            throw new \Exception('Invalid field mapping for property "%s": %s', $property, serialize($mapping));
        }
        return $mapping;
    }

    private function validateFieldMapping(array $mapping)
    {
        if($mapping['name'] === null) {
            return false;
        }
        $validTypes = ['keyword', 'text', 'binary', 'unIndexed', 'unStored', 'array'];
        if(!in_array($mapping['type'], $validTypes)) {
            return false;
        }
        return true;
    }


    private function verifyNotCompiled()
    {
        if($this->compiled) {
            throw new \Exception('Class metadata cannot be changed after it has been compiled');
        }
    }

    /**
     * @param array $indexes
     * @return $this
     */
    public function mapIndexes(array $indexes)
    {
        $this->verifyNotCompiled();
        $this->indexes = $indexes;
        return $this;
    }

    private function compileFieldMapping(ClassMetadata $classMetadata, $name, array $mapping)
    {
        if($mapping['indexes'] === null) {
            $mapping['indexes'] = $classMetadata->getIndexes();
        }
        return $mapping;
    }

    private function compileFieldMappings(array $fieldMappings)
    {
        foreach($fieldMappings as $name => $mapping) {
            $fieldMappings[$name] = $this->compileFieldMapping($this, $name, $mapping);
        }
        return $fieldMappings;
    }

    private function compileFilterMappings(ClassMetadata $classMetadata, array $filterMappings)
    {
        foreach($filterMappings as $key => $filterMapping) {
            if($filterMapping['indexes'] === null) {
                $filterMapping['indexes'] = $classMetadata->getIndexes();
            }
            $filterMappings[$key] = $filterMapping;
        }
        return $filterMappings;
    }


    private function compileFieldNamesByIndex(array $indexes, array $fieldMappings)
    {
        $fieldNamesByIndex = [];
        foreach($indexes as $index) {
            $fieldNamesByIndex[$index] = [];
            foreach($fieldMappings as $fieldName => $fieldMapping) {
                if(!in_array($index, $fieldMapping['indexes'])) {
                    continue;
                }
                $fieldNamesByIndex[$index][] = $fieldName;
            }
        }
        return $fieldNamesByIndex;
    }

    protected function doCompile(ClassMetadata $classMetadata)
    {
        $classMetadata->fieldMappings = $this->compileFieldMappings($classMetadata->getFieldMappings());
        $classMetadata->fieldNamesByIndex = $this->compileFieldNamesByIndex($classMetadata->getIndexes(), $classMetadata->fieldMappings);
        $classMetadata->filterMappings = $this->compileFilterMappings($classMetadata, $classMetadata->getFilterMappings());
    }

    public function compile()
    {
        $this->verifyNotCompiled();

        $classMetadata = clone $this;
        $classMetadata->compiled = true;

        $this->doCompile($classMetadata);

        return $classMetadata;
    }
}