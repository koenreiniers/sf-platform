<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;

use \ZendSearch\Lucene\Search\Query\Preprocessing;

class Phrase extends Value
{
    /**
     * @var string
     */
    protected $phrase;

    /**
     * Phrase constructor.
     * @param $phrase
     */
    public function __construct($phrase)
    {
        $this->phrase = $phrase;
    }

    public function getQuery()
    {
        return new Preprocessing\Phrase($this->phrase, null, null);
    }

    public function __toString()
    {
        return sprintf('"%s"', $this->phrase);
    }
}