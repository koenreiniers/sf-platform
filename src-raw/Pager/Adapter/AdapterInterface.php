<?php
namespace Raw\Pager\Adapter;

interface AdapterInterface
{
    /**
     * @return int
     */
    public function getTotalCount();

    /**
     * @param int $offset
     * @param int $limit
     * @return array|\Traversable
     */
    public function getSlice($offset, $limit);
}