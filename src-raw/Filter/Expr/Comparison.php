<?php
namespace Raw\Filter\Expr;

class Comparison extends Base
{
    const EQ = '==';
    const NEQ = '!=';
    const GT = '>';
    const GTE = '>=';
    const LT = '<';
    const LTE = '<=';
    const LIKE = 'LIKE';



    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Comparison constructor.
     * @param string $field
     * @param string $operator
     * @param mixed $value
     */
    public function __construct($field, $operator, $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}