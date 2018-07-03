<?php
namespace Raw\Bundle\LuceneBundle\Lucene;

use Raw\Bundle\LuceneBundle\Lucene\Expression\Expression;
use Raw\Bundle\LuceneBundle\Lucene\Expression\ZendQueryExpression;
use ZendSearch\Lucene\Search\QueryParser;

class ExpressionParser
{
    /**
     * @var QueryParser
     */
    private $queryParser;

    public function __construct()
    {
        $this->queryParser = new QueryParser();
    }

    /**
     * @param string $queryStr
     * @param null $encoding
     * @return Expression
     */
    public function parse($queryStr, $encoding = null)
    {
        $query = $this->queryParser->parse($queryStr, $encoding);
        return new ZendQueryExpression($query);
    }
}