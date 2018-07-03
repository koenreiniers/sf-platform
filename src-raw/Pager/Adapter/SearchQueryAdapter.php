<?php
namespace Raw\Pager\Adapter;

use Raw\Search\SearchQuery;
use Symfony\Component\VarDumper\VarDumper;

class SearchQueryAdapter implements AdapterInterface
{
    /**
     * @var SearchQuery
     */
    private $searchQuery;

    /**
     * @var int
     */
    private $totalCount;

    /**
     * SearchQueryAdapter constructor.
     * @param SearchQuery $searchQuery
     */
    public function __construct(SearchQuery $searchQuery)
    {
        $this->searchQuery = $searchQuery;
    }

    public function getTotalCount()
    {
        if($this->totalCount === null) {

            $countQuery = clone $this->searchQuery;
            $countQuery->setMaxResults(null);
            $countQuery->setFirstResult(0);

            $this->totalCount = $countQuery->count();
        }
        return $this->totalCount;
    }

    public function getSlice($offset, $limit)
    {
        $this->searchQuery
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        return $this->searchQuery->execute();
    }
}