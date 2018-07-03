<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Query;

use Raw\Bundle\LuceneBundle\Lucene\Expression\Expression;
use ZendSearch\Lucene\Index;

class Query
{
    /**
     * @var Index
     */
    private $index;

    /**
     * @var Expression
     */
    private $expression;

    /**
     * Query constructor.
     * @param Index $index
     * @param Expression $expression
     */
    public function __construct(Index $index, Expression $expression)
    {
        $this->index = $index;
        $this->expression = $expression;
    }

    /**
     * @return QueryResult
     */
    public function execute()
    {
        $hits = $this->index->find((string)$this->expression);
        return new QueryResult($hits);
    }

    /**
     * @return QueryResult
     */
    public function getResult()
    {
        return $this->execute();
    }
}