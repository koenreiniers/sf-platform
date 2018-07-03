<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Document;

use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Document\Field;

class DocumentBuilder extends Document
{
    /**
     * @param string $name
     * @param string $value
     * @param string $encoding
     * @return $this
     */
    public function addTextField($name, $value, $encoding = 'UTF-8')
    {
        return $this->addField(Field::text($name, $value, $encoding));
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $encoding
     * @return $this
     */
    public function addKeywordField($name, $value, $encoding = 'UTF-8')
    {
        return $this->addField(Field::keyword($name, $value, $encoding));
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addBinaryField($name, $value)
    {
        return $this->addField(Field::binary($name, $value));
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $encoding
     * @return $this
     */
    public function addUnIndexedField($name, $value, $encoding = 'UTF-8')
    {
        return $this->addField(Field::unIndexed($name, $value, $encoding));
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $encoding
     * @return $this
     */
    public function addUnStoredField($name, $value, $encoding = 'UTF-8')
    {
        return $this->addField(Field::unStored($name, $value, $encoding));
    }

    /**
     * @return DocumentBuilder
     */
    public function getDocument()
    {
        $document = clone $this;
        return $document;
    }
}