<?php
namespace Raw\Search\Mapping;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Document
{
    public $type = 'orm';

    public $indexes = [];
}