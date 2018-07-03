<?php
namespace Raw\Pager\Adapter;

use Raw\Pager\Pager;
use Raw\Search\SearchQuery;
use Symfony\Component\VarDumper\VarDumper;

class EmptyAdapter implements AdapterInterface
{
    public function getTotalCount()
    {
        return 0;
    }

    public function getSlice($offset, $limit)
    {
        return [];
    }
}