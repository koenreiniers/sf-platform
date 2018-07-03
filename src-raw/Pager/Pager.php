<?php
namespace Raw\Pager;

use Raw\Pager\Adapter\AdapterInterface;
use Raw\Pager\Adapter\ForkAdapter;
use Raw\Pager\Event\BuildViewEvent;
use Raw\Pager\Event\HandleRequestEvent;

class Pager
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var bool
     */
    private $outdated = true;

    /**
     * @var PagerConfig
     */
    private $config;

    /**
     * @var PagerFactory
     */
    private $factory;

    /**
     * @var array
     */
    private $items;

    /**
     * @var int
     */
    private $pageSize = 10;

    /**
     * @var int
     */
    private $currentPage = 1;

    /**
     * @var int
     */
    private $totalCount;

    /**
     * Pager constructor.
     * @param PagerConfig $config
     */
    public function __construct(PagerConfig $config)
    {
        $this->config = $config;
        $this->factory = $config->getFactory();
        $this->adapter = $config->getAdapter();
        $this->setCurrentPage(1);
        $this->setPageSize(10);
    }

    /**
     * @return PagerConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return int
     */
    public function getMaxPageSize()
    {
        return $this->config->getMaxPageSize();
    }

    /**
     * @return null|string
     */
    public function getNamespace()
    {
        return $this->config->getNamespace();
    }

    /**
     * @param mixed $request
     */
    public function handleRequest($request = null)
    {
        $this->config->getRequestHandler()->handle($this, $request);
        $this->config->getEventDispatcher()->dispatch(PagerEvents::HANDLE_REQUEST, new HandleRequestEvent($this, $request));
    }

    /**
     * @param mixed $request
     */
    public function terminateRequest($request = null)
    {
        $this->config->getRequestHandler()->terminate($this, $request);
    }

    public function apply()
    {

    }

    /**
     * @return array|\Traversable
     */
    public function getItems()
    {
        if($this->outdated) {
            $this->minMaxPagination();
            $limit = $this->getPageSize();


            $offset = $this->getOffset();

            $this->items = $this->adapter->getSlice($offset, $limit);

            $this->outdated = false;
        }

        return $this->items;
    }

    public function getOffset()
    {
        return ($this->getCurrentPage() - 1) * $this->getPageSize();
    }

    /**
     * @return int
     */
    public function getAmountOfPages()
    {
        $total = $this->getTotalCount();
        return (int)ceil($total / $this->getPageSize());
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        if($this->totalCount === null) {
            $this->totalCount = $this->adapter->getTotalCount();
        }
        return $this->totalCount;
    }

    /**
     * @return int
     */
    public function getNextPage()
    {
        return min($this->getAmountOfPages(), $this->getCurrentPage() + 1);
    }

    /**
     * @return int
     */
    public function getPreviousPage()
    {
        return max(1, $this->getCurrentPage() - 1);
    }

    /**
     * @param int $pageSize
     * @return Pager
     */
    public function setPageSize($pageSize)
    {
        $pageSize = (int)max(1, $pageSize);
        if($pageSize === $this->pageSize) {
            return $this;
        }
        $this->outdated = true;
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    private function minMaxPagination()
    {
        $this->currentPage = min($this->currentPage, $this->getAmountOfPages());
        $this->currentPage = (int)max(1, $this->currentPage);
    }

    /**
     * @param int $currentPage
     * @return Pager
     */
    public function setCurrentPage($currentPage)
    {
        if($currentPage === $this->currentPage) {
            return $this;
        }
        $this->outdated = true;
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * @return null
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @return PagerView
     */
    public function createView()
    {
        $view = new PagerView($this);
        $this->config->getEventDispatcher()->dispatch(PagerEvents::BUILD_VIEW, new BuildViewEvent($this, $view));
        return $view;
    }

    public function fork()
    {
        return $this->factory->createBuilder(new ForkAdapter($this))
            ->setNamespace($this->getNamespace())
            ->setRequestHandler($this->config->getRequestHandler())
            ->setMaxPageSize($this->getMaxPageSize())
            ->getPager()
            ->setCurrentPage($this->getCurrentPage())
            ->setPageSize($this->getPageSize());
    }
}