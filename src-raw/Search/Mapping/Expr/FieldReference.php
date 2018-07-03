<?php
namespace Raw\Search\Mapping\Expr;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class FieldReference extends Expr
{
    public $fieldName;

    public function resolve(ExprResolver $resolver)
    {
        return $resolver->getDocument()->getFieldValue($this->fieldName);
    }
}