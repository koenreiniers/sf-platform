<?php
namespace Raw\Search\Mapping;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Filter
{
    public $expr;

    public $indexes;
}