<?php
namespace Raw\Filter;

interface FilterStorageInterface
{
    /**
     * @param Filter $filter
     * @return $this
     */
    public function addFilter(Filter $filter);

    /**
     * @param Filter $filter
     * @return $this
     */
    public function removeFilter(Filter $filter);

    /**
     * @return Filter[]
     */
    public function getFilters();

    /**
     * @return void
     */
    public function clear();
}