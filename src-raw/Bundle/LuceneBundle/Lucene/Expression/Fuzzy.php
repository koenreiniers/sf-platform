<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;



class Fuzzy extends Value
{
    /**
     * @var Term
     */
    private $term;

    /**
     * @var null|float
     */
    private $similarity;

    public function __construct($term, $similarity = null)
    {
        $this->term = $term;
        $this->similarity = $similarity;
    }

    public function __toString()
    {
        $similarity = $this->similarity ?: '';
        return $this->term.'~'.$similarity;
    }
}