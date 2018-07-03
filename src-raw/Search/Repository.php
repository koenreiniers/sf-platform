<?php
namespace Raw\Search;

use Raw\Search\Hydrator\HydratorInterface;
use ZendSearch\Lucene\Index;

class Repository
{
    /**
     * @var SearchIndex
     */
    private $index;

    /**
     * @var HydratorInterface
     */
    private $defaultHydrator;

    /**
     * Repository constructor.
     * @param Index $index
     */
    public function __construct(SearchIndex $index)
    {
        $this->index = $index;
    }

    public function setDefaultHydrator(HydratorInterface $defaultHydrator)
    {
        $this->defaultHydrator = $defaultHydrator;
        return $this;
    }

    public function findBy(array $criteria)
    {
        $searchQuery = $this->queryBy($criteria);
        return $searchQuery->execute();
    }

    private function isFuzzy($value)
    {
        return $this->contains($value, '~');
    }
    private function isWildcard($value)
    {
        return $this->contains($value, '*') || $this->contains($value, '&');
    }

    private function contains($value, $char)
    {
        return strpos($value, $char) !== false;
    }

    private function quote($value)
    {
        if(!$this->contains($value, ' ')) {
            return $value;
        }
        if($this->isFuzzy($value)) {
            return $value;
        }
        if($this->isWildcard($value)) {
            return $value;
        }

        return sprintf('"%s"', $value);
    }

    public function queryBy(array $criteria)
    {
        $exprs = [];
        foreach($criteria as $key => $value) {
            $value = $this->quote($value);

            $exprs[] = sprintf('%s:%s', $key, $value);
        }
        $query = implode(' AND ', $exprs);

        return $this->query($query);
    }

    /**
     * @param mixed $query
     * @return SearchQuery
     */
    public function query($query)
    {
        $searchQuery = new SearchQuery($this->index, $query);

        if($this->defaultHydrator !== null) {
            $searchQuery->setHydrator($this->defaultHydrator);
        }

        return $searchQuery;
    }
}