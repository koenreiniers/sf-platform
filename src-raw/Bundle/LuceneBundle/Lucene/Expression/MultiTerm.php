<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Expression;

use Symfony\Component\VarDumper\VarDumper;
use ZendSearch\Lucene\Search\Query\Preprocessing as PreProcess;
use ZendSearch\Lucene\Search\Query\Boolean as BooleanQuery;


class MultiTerm extends Value
{
    /**
     * @var string[]
     */
    protected $terms;

    /**
     * MultiTerm constructor.
     * @param array $terms
     */
    public function __construct(array $terms)
    {

        $this->terms = [];
        foreach($terms as $term) {
            if($term instanceof EmptyExpression) {
                continue;
            }
            if(!$term instanceof Expression) {
                throw new \InvalidArgumentException();
            }
            $this->terms[] = $term;

        }
    }

    public function getQuery()
    {
        $query = new BooleanQuery();
        foreach($this->terms as $term) {
            $query->addSubquery($term->getQuery());
        }
        return $query;
    }

    public function __toString()
    {
        return implode(' ', $this->terms);
    }
}