<?php
namespace Raw\Filter\Expr;

class Composite extends Base
{
    const TYPE_AND = 'AND';
    const TYPE_OR = 'OR';
    const TYPE_BETWEEN = 'BETWEEN';

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $expressions;

    /**
     * Composite constructor.
     * @param string $type
     * @param Base[] $expressions
     */
    public function __construct($type, array $expressions = [])
    {
        $this->type = $type;
        $this->addMultiple($expressions);
    }

    public function addMultiple(array $expressions)
    {
        foreach($expressions as $expression) {
            $this->add($expression);
        }
        return $this;
    }

    public function add(Base $expression)
    {
        $this->expressions[] = $expression;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getExpressions()
    {
        return $this->expressions;
    }
}