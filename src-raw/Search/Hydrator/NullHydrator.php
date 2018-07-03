<?php
namespace Raw\Search\Hydrator;

use Symfony\Component\VarDumper\VarDumper;
use ZendSearch\Lucene\Document;

class NullHydrator implements HydratorInterface
{

    public function hydrateAll(array $hits)
    {


        $results = [];
        foreach($hits as $hit) {
            $results[] = $hit->getDocument();
        }
        return $results;
    }

    public function hydrate(Document $document)
    {
        return $document;
    }
}