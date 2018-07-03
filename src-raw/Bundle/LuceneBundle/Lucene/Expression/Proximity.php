<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;



class Proximity extends Expression
{
    /**
     * @var Term[]
     */
    private $terms;

    /**
     * @var int
     */
    private $proximity;

    public function __construct($proximity, array $terms)
    {
        $this->terms = [];
        foreach($terms as $term) {
            if(is_string($term)) {
                $term = new Term($term);
            }
            if(!$term instanceof Term) {
                throw new \InvalidArgumentException('Terms should be either strings or "%s"', Term::class);
            }
            $this->terms[] = $term;
        }
        $this->proximity = $proximity;
    }


    public function __toString()
    {
        return sprintf('"%s"~%s', implode(' ', $this->terms), $this->proximity);
    }
}