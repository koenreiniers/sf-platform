<?php
namespace Raw\Pager\Adapter;

use Raw\Pager\Pager;
use Raw\Search\SearchQuery;
use Symfony\Component\VarDumper\VarDumper;

class ForkAdapter implements AdapterInterface
{
    /**
     * @var Pager
     */
    private $pager;

    /**
     * ForkAdapter constructor.
     * @param Pager $pager
     */
    public function __construct(Pager $pager)
    {
        $this->pager = $pager;
    }

    public function getTotalCount()
    {
        return $this->pager->getTotalCount();
    }

    public function getSlice($offset, $limit)
    {
        return $this->pager->getItems();
    }
}