<?php
namespace Raw\Filter\Expr;

class Literal extends Base
{
    /**
     * @var string
     */
    private $literal;

    /**
     * Literal constructor.
     * @param string $literal
     */
    public function __construct($literal)
    {
        $this->literal = $literal;
    }

    /**
     * @return string
     */
    public function getLiteral()
    {
        return $this->literal;
    }


}