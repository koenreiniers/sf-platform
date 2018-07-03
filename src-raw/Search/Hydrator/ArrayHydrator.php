<?php
namespace Raw\Search\Hydrator;

use ZendSearch\Lucene\Document;

class ArrayHydrator implements HydratorInterface
{
    public function hydrateAll(array $hits)
    {
        $res = [];
        foreach($hits as $hit) {
            $res[] = $this->hydrate($hit->getDocument());
        }
        return $res;
    }

    public function hydrate(Document $document)
    {
        $data = [];
        $fieldNames = $document->getFieldNames();
        foreach($fieldNames as $fieldName) {
            $data[$fieldName] = $document->getFieldValue($fieldName);
        }
        return $data;
    }
}