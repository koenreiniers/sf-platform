<?php
namespace Raw\Search\Mapping\Expr;


/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class GenerateUrl extends Expr
{
    public $route;

    public $params = [];

    public function resolve(ExprResolver $resolver)
    {
        $route = $resolver->resolve($this->route);
        $params = $resolver->resolve($this->params);
        return $resolver->getContainer()->get('router')->generate($route, $params);
    }
}