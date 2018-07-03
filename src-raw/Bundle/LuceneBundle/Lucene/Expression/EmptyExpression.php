<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;



class EmptyExpression extends Value
{
    public function __toString()
    {
        return '';
    }
}