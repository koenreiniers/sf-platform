<?php
namespace Raw\Component\Grid\DataSource;

use Raw\Component\Grid\DataSource\Adapter\AdapterInterface;

class DataSource
{
    /**
     * @var int int
     */
    protected $page = 1;

    /**
     * @var int
     */
    protected $limit = 50;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * DataSource constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    public function setParameter($name, $value)
    {
        $this->adapter->setParameter($name, $value);
        return $this;
    }

    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    public function setPageSize($pageSize)
    {
        $this->limit = $pageSize;
        return $this;
    }

    public function orderBy($field, $direction = 'ASC')
    {
        $this->adapter->orderBy($field, $direction);
        return $this;
    }

    /**
     * @return Data
     */
    public function getData()
    {
        $offset = ($this->page - 1) * $this->limit;
        return $this->adapter->getData($offset, $this->limit);
    }
}