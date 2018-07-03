<?php
namespace Raw\Search\Mapping;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class Field
{
    public $type;

    public $indexes;

    public $name;

    public $encoding = 'UTF-8';
}