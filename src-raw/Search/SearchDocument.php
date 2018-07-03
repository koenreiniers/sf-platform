<?php
namespace Raw\Search;

use ZendSearch\Lucene\Document;

class SearchDocument extends Document
{
    public function addUnindexed($name, $value, $encoding = 'UTF-8')
    {
        return $this->addField(Document\Field::unIndexed($name, $value, $encoding));
    }
    public function addUnstored($name, $value, $encoding = 'UTF-8')
    {
        return $this->addField(Document\Field::unStored($name, $value, $encoding));
    }
    public function addText($name, $value, $encoding = 'UTF-8')
    {
        return $this->addField(Document\Field::text($name, $value, $encoding));
    }
    public function addKeyword($name, $value, $encoding = 'UTF-8')
    {
        return $this->addField(Document\Field::keyword($name, $value, $encoding));
    }
    public function addBinary($name, $value)
    {
        return $this->addField(Document\Field::binary($name, $value));
    }
}