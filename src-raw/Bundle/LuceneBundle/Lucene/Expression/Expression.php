<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;

use ZendSearch\Lucene\Search\Query\AbstractQuery;
use ZendSearch\Lucene\Search\QueryParser;

abstract class Expression
{
    abstract public function __toString();

    /**
     * @return \ZendSearch\Lucene\Search\Query\AbstractQuery
     */
    public function createQuery($useCache = false)
    {
        $str = (string)$this;
        $query = QueryParser::parse($str);
        return $query;
    }

    /**
     * @return AbstractQuery
     */
    public function getQuery()
    {
        throw new \Exception('Nog niet geimplementeerd');
    }
}