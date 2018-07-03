<?php
namespace Raw\Filter;



abstract class FilterAdapter
{
    abstract public function dispatch(Expr\Base $expression);
}