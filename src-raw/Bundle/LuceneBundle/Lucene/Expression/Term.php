<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;

use ZendSearch\Lucene\Search\Query\Term as TermQuery;
use \ZendSearch\Lucene\Search\Query\Preprocessing;

class Term extends Value
{
    /**
     * @var string
     */
    protected $term;

    /**
     * Value constructor.
     * @param string $term
     */
    public function __construct($term)
    {
        if(is_string($term) && strpos($term, ' ') !== false) {
            throw new \Exception('Terms should not contain any spaces');
        }
        $this->term = $term;
    }

    public function getQuery()
    {
        return new Preprocessing\Term($this->term, null, null);
    }

    public function __toString()
    {
        return $this->term;
    }
}