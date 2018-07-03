<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;

use \ZendSearch\Lucene\Search\Query\Preprocessing;

class Range extends Value
{
    /**
     * @var string
     */
    private $start;

    /**
     * @var string
     */
    private $end;

    /**
     * @var bool
     */
    private $inclusive;

    /**
     * Range constructor.
     * @param string $start
     * @param string $end
     * @param bool $inclusive
     */
    public function __construct(Term $start, Term $end, $inclusive = true)
    {
        $this->start = $start;
        $this->end = $end;
        $this->inclusive = $inclusive;
    }

    public function getQuery()
    {
        return new \ZendSearch\Lucene\Search\Query\Range($this->start, $this->end, $this->inclusive);
    }

    public function __toString()
    {
        $str = sprintf('%s TO %s', $this->start, $this->end);

        if($this->inclusive) {
            $str = '['.$str.']';
        } else {
            $str = '{'.$str.'}';
        }
        return $str;
    }
}