<?php
namespace Raw\Filter;

use Symfony\Component\VarDumper\VarDumper;

class ExpressionBuilder
{
    public function andX($x = null)
    {
        return new Expr\Composite(Expr\Composite::TYPE_AND, func_get_args());
    }

    public function gt($x, $y)
    {
        return new Expr\Comparison($x, Expr\Comparison::GT, $y);
    }

    public function gte($x, $y)
    {
        return new Expr\Comparison($x, Expr\Comparison::GTE, $y);
    }

    public function between($x, $lower, $upper)
    {
        return $this->andX(
            $this->gte($x, $lower),
            $this->lte($x, $upper)
        );
    }

    public function like($x, $y)
    {
        return new Expr\Comparison($x, Expr\Comparison::LIKE, $y);
    }

    public function lt($x, $y)
    {
        return new Expr\Comparison($x, Expr\Comparison::LT, $y);
    }

    public function lte($x, $y)
    {
        return new Expr\Comparison($x, Expr\Comparison::LTE, $y);
    }

    public function neq($x, $y)
    {
        return new Expr\Comparison($x, Expr\Comparison::NEQ, $y);
    }

    public function eq($x, $y)
    {
        return new Expr\Comparison($x, Expr\Comparison::EQ, $y);
    }

    public function orX($x = null)
    {
        return new Expr\Composite(Expr\Composite::TYPE_OR, func_get_args());
    }

    public function literal($literal)
    {
        return new Expr\Literal($literal);
    }
}