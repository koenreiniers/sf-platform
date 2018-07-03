<?php
namespace Raw\Search;

use Raw\Search\Hydrator\HydratorInterface;
use Raw\Search\Hydrator\NullHydrator;
use ZendSearch\Lucene\Index;
use Raw\Search\Query\QueryHit;

class SearchQuery
{
    /**
     * @var Index
     */
    private $index;

    /**
     * @var mixed
     */
    private $query;

    /**
     * @var int
     */
    private $firstResult = 0;

    /**
     * @var int|null
     */
    private $maxResults = null;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @var bool
     */
    private $dirty = true;

    /**
     * @var QueryHit[]
     */
    private $hits;

    /**
     * @var array
     */
    private $sort = [];

    /**
     * SearchQuery constructor.
     * @param SearchIndex $index
     * @param mixed $query
     */
    public function __construct(SearchIndex $index, $query)
    {
        $this->index = $index;
        $this->query = $query;
        $this->hydrator = new NullHydrator();
    }

    public function getQueryString()
    {
        return $this->query;
    }

    private function setDirty()
    {
        $this->dirty = true;
    }

    /**
     * @param int $firstResult
     * @return $this
     */
    public function setFirstResult($firstResult)
    {
        $this->setDirty();

        $this->firstResult = max(0, (int)$firstResult);
        return $this;
    }

    /**
     * @param int|null $maxResults
     * @return $this
     */
    public function setMaxResults($maxResults = null)
    {
        $this->setDirty();

        if(is_numeric($maxResults)) {
            $maxResults = (int)max(0, $maxResults);
        }
        $this->maxResults = $maxResults;
        return $this;
    }

    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }

    public function addSort($field, $type = null, $direction = null)
    {
        $this->setDirty();
        $this->sort[] = $field;
        if($type !== null) {
            $this->sort[] = $type;
        }
        if($direction !== null) {
            $this->sort[] = $direction;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->getHits());
    }

    protected function getHits()
    {
        if($this->dirty) {
            $args = [$this->query];
            $args = array_merge($args, $this->sort);

            $hits = call_user_func_array([$this->index, 'find'], $args);

            if($this->firstResult > 0) {
                $hits = array_slice($hits, $this->firstResult);
            }
            if($this->maxResults !== null) {
                $hits = array_slice($hits, 0, $this->maxResults);
            }
            $this->hits = $hits;
            $this->dirty = false;
        }

        return $this->hits;
    }

    /**
     * @param HydratorInterface|null $hydrator
     * @return \mixed[]
     */
    public function execute(HydratorInterface $hydrator = null)
    {
        $this->hydrator = $hydrator ?: $this->hydrator;


        $hits = $this->getHits();


        return $this->hydrator->hydrateAll($hits);
    }
}