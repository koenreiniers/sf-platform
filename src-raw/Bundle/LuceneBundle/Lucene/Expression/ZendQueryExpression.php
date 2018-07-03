<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;

use ZendSearch\Lucene\Search\Query\AbstractQuery;

class ZendQueryExpression extends Expression
{
    /**
     * @var AbstractQuery
     */
    private $query;

    /**
     * ZendQueryExpression constructor.
     * @param AbstractQuery $query
     */
    public function __construct(AbstractQuery $query)
    {
        $this->query = $query;
    }

    public function __toString()
    {
        return $this->query->__toString();
    }
}