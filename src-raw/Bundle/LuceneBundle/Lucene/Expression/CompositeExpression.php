<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;

use ZendSearch\Lucene\Search\Query\Boolean as BooleanQuery;

class CompositeExpression extends Expression
{
    const TYPE_AND = 'AND';
    const TYPE_OR = 'OR';

    /**
     * @var Expression[]
     */
    private $expressions;

    /**
     * @var string
     */
    private $type;

    public function __construct(array $expressions, $type = self::TYPE_AND)
    {
        $this->expressions = $expressions;
        $this->type = $type;
    }

    public function getQuery()
    {
        $query = new BooleanQuery();
        $sign = $this->type === self::TYPE_OR ?  null : true;

        foreach($this->expressions as $expression) {
            $query->addSubquery($expression->getQuery(), $sign);
        }

        return $query;
    }

    public function __toString()
    {
        return '('.implode(' '.$this->type.' ', $this->expressions).')';
    }
}