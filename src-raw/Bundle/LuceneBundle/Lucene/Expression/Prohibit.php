<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;



class Prohibit extends Value
{
    /**
     * @var Term
     */
    private $term;

    public function __construct(Term $term)
    {
        $this->term = $term;
    }

    public function __toString()
    {
        return '-'.$this->term;
    }
}