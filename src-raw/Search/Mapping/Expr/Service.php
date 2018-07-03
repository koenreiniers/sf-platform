<?php
namespace Raw\Search\Mapping\Expr;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class Service extends Expr
{
    public $id;

    public function resolve(ExprResolver $resolver)
    {
        return $resolver->getContainer()->get($this->id);
    }
}