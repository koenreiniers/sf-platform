<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;



class Wildcard extends Value
{
    /**
     * @var Value
     */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }
}