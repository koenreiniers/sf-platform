<?php
namespace Raw\Search\Document;

use Raw\Search\Mapping\ClassMetadata;
use ZendSearch\Lucene\Document;

class DocumentFactory
{

    const FIELD_RESOURCE_NAME = '__resourceName';
    const FIELD_RESOURCE_ID = '__resourceId';


    protected function newDocument()
    {
        return new Document();
    }

    public function createResourceDocument(ClassMetadata $classMetadata, $resource, array $fieldMappings)
    {
        $document = $this->newDocument();

        $resourceName = $classMetadata->getResourceName();
        $resourceId = $classMetadata->getResourceId($resource);

        $document->addField($this->createField('keyword', self::FIELD_RESOURCE_NAME, $resourceName));
        $document->addField($this->createField('keyword', self::FIELD_RESOURCE_ID, $resourceId));

        foreach($fieldMappings as $fieldName => $fieldMapping) {
            $value = isset($fieldMapping['value']) ? $fieldMapping['value'] : $classMetadata->getPropertyValue($resource, $fieldMapping['property']);
            $field = $this->createField($fieldMapping['type'], $fieldName, $value, $fieldMapping['encoding']);
            $document->addField($field);
        }

        return $document;
    }

    public function createField($type, $name, $value, $encoding = 'UTF-8')
    {
        switch($type) {
            case 'array':
                $value = serialize($value);
                $fn = [Document\Field::class, 'unIndexed'];
                break;
            default:
                $fn = [Document\Field::class, $type];
                break;
        }




        $args = [$name, $value];
        if($type !== 'binary') {
            $args[] = $encoding;
        }

        $field = call_user_func_array($fn, $args);
        return $field;
    }
}