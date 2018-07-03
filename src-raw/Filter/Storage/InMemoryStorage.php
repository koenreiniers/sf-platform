<?php
namespace Raw\Filter\Storage;

use Raw\Filter\Filter;
use Raw\Filter\FilterStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class InMemoryStorage implements FilterStorageInterface
{

    /**
     * @var Filter[]
     */
    private $filters = [];


    public function clear()
    {
        $this->filters = [];
    }




    /**
     * @param Filter $filter
     * @return $this
     */
    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
        return $this;
    }

    /**
     * @param Filter $filter
     * @return $this
     */
    public function removeFilter(Filter $filter)
    {
        $key = array_search($filter, $this->filters);
        if($key !== false) {
            unset($this->filters[$key]);
        }
        return $this;

    }

    /**
     * @return Filter[]
     */
    public function getFilters()
    {

        return $this->filters;
    }
}