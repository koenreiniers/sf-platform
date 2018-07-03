<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Document;

use ZendSearch\Lucene\Document;

class DocumentFactory
{
    /**
     * @return Document
     */
    public function create()
    {
        return $this->createBuilder()->getDocument();
    }

    /**
     * @return DocumentBuilder
     */
    public function createBuilder()
    {
        return new DocumentBuilder();
    }
}