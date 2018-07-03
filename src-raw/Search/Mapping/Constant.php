<?php
namespace Raw\Search\Mapping;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Constant
{
    public $name;

    public $type;

    public $value;

    public $indexes;

    public $encoding = 'UTF-8';
}