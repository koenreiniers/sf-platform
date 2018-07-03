<?php
namespace Raw\Pager\Adapter;

use Raw\Pager\Pager;
use Raw\Search\SearchQuery;
use Symfony\Component\VarDumper\VarDumper;

class InMemoryAdapter implements AdapterInterface
{
    /**
     * @var array
     */
    private $items;

    /**
     * InMemoryAdapter constructor.
     * @param array $items
     */
    public function __construct($items)
    {
        $this->items = $items;
    }


    public function getTotalCount()
    {
        return count($this->items);
    }

    public function getSlice($offset, $limit)
    {
        return array_slice($this->items, $offset, $limit);
    }
}