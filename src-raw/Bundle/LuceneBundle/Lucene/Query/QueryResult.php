<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Query;

use ZendSearch\Lucene\Index;
use ZendSearch\Lucene\Search\QueryHit;

class QueryResult implements \Countable
{
    /**
     * @var QueryHit[]
     */
    private $hits;

    public function __construct(array $hits)
    {
        $this->hits = $hits;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->hits);
    }

    /**
     * @return array|\ZendSearch\Lucene\Search\QueryHit[]
     */
    public function getHits()
    {
        return $this->hits;
    }
}