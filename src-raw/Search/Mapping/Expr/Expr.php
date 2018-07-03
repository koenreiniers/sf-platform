<?php
namespace Raw\Search\Mapping\Expr;

use ZendSearch\Lucene\Document;

abstract class Expr
{
    abstract public function resolve(ExprResolver $resolver);
}