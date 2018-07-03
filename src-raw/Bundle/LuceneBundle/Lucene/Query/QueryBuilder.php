<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Query;

use Raw\Bundle\LuceneBundle\Lucene\Expression\EmptyExpression;
use Raw\Bundle\LuceneBundle\Lucene\Expression\Expression;
use Raw\Bundle\LuceneBundle\Lucene\Expression\ExpressionBuilder;
use Raw\Bundle\LuceneBundle\Lucene\ExpressionParser;
use ZendSearch\Lucene\Index;

class QueryBuilder
{
    /**
     * @var Expression
     */
    private $expr;

    /**
     * @var ExpressionBuilder
     */
    private $eb;

    /**
     * @var ExpressionParser
     */
    private $parser;

    /**
     * @var Index
     */
    private $index;

    public function __construct(Index $index)
    {
        $this->index = $index;
        $this->expr = new EmptyExpression();
        $this->eb = new ExpressionBuilder();
        $this->parser = new ExpressionParser();
    }

    public function expr()
    {
        return $this->eb;
    }

    private function parseExpr($expr)
    {
        if(is_string($expr)) {
            $expr = $this->parser->parse($expr);
        }
        return $expr;
    }

    public function where($expr)
    {
        $expr = $this->parseExpr($expr);
        $this->expr = $this->eb->multi($this->expr, $expr);
        return $this;
    }

    public function andWhere($expr)
    {
        $expr = $this->parseExpr($expr);
        $this->expr = $this->eb->andX($this->expr, $expr);
        return $this;
    }

    public function getQuery()
    {
        $expression = clone $this->expr;
        $query = new Query($this->index, $expression);
        return $query;
    }
}