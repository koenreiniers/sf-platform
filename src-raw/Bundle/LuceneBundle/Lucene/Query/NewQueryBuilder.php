<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Query;

use ZendSearch\Lucene\Search\Query\AbstractQuery;
use ZendSearch\Lucene\Search\Query as ZendQuery;
use ZendSearch\Lucene\Search\Query\Preprocessing as Preprocess;

class NewQueryBuilder
{

    /**
     * @var AbstractQuery[]
     */
    private $queries = [];

    public function __construct(AbstractQuery $query = null)
    {
        $this->query = null;
    }

    public function equals($field, $value)
    {
        $className = Preprocess\Term::class;
        $isPhrase = strpos($value, ' ') !== false;
        if($isPhrase) {
            $className = Preprocess\Phrase::class;
        }
        return new $className($value, '', $field);
    }

    public function phrase($phrase)
    {
        return new Preprocess\Phrase($phrase, '', null);
    }

    public function term($word)
    {
        return new Preprocess\Term($word, '', null);
    }

    public function andX($x = null)
    {
        return $this->boolean(func_get_args(), true);
    }

    public function boolean(array $subqueries, $sign = null)
    {
        $query = new ZendQuery\Boolean();

        foreach($subqueries as $subquery) {
            $query->addSubquery($subquery, $sign);
        }
        $this->queries[] = $query;
        return $this;
    }

    public function orX($x = null)
    {
        return $this->boolean(func_get_args());
    }

    public function getQuery()
    {
        if(count($this->queries) === 1) {
            return $this->queries[0];
        }
        $query = new ZendQuery\Boolean();
        foreach($this->queries as $subQuery) {
            $query->addSubquery($subQuery);
        }
        return $query;
    }
}