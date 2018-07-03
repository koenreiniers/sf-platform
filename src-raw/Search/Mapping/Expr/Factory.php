<?php
namespace Raw\Search\Mapping\Expr;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class Factory extends Expr
{
    public $method;

    public $arguments;

    public function resolve(ExprResolver $resolver)
    {
        $method = $resolver->resolve($this->method);
        $arguments = $resolver->resolve($this->arguments);
        return call_user_func_array($method, $arguments);
    }
}