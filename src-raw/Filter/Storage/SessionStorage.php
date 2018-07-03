<?php
namespace Raw\Filter\Storage;

use Raw\Filter\Filter;
use Raw\Filter\FilterStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\VarDumper\VarDumper;

class SessionStorage implements FilterStorageInterface
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var Filter[]
     */
    private $filters;

    /**
     * @var string
     */
    private $sessionKey;

    /**
     * SessionStorage constructor.
     * @param SessionInterface $session
     * @param string $sessionKey
     */
    public function __construct(SessionInterface $session, $sessionKey = '_raw_filter')
    {
        $this->session = $session;
        $this->sessionKey = $sessionKey;
    }

    public function clear()
    {
        $this->filters = null;
        $this->session->set($this->sessionKey, null);
    }

    private function init()
    {
        if($this->filters === null) {
            $this->filters = $this->session->get($this->sessionKey, []);
            if($this->filters === null) {
                $this->filters = [];
            }

        }
    }

    private function persist()
    {
        $this->init();
        $this->filters = array_values($this->filters);
        $this->session->set($this->sessionKey, $this->filters);
    }

    /**
     * @param Filter $filter
     * @return $this
     */
    public function addFilter(Filter $filter)
    {
        $this->init();
        $this->filters[] = $filter;
        $this->persist();
        return $this;
    }

    /**
     * @param Filter $filter
     * @return $this
     */
    public function removeFilter(Filter $filter)
    {
        $this->init();

        $key = array_search($filter, $this->filters);
        if($key !== false) {
            unset($this->filters[$key]);
            $this->persist();
        }
        return $this;

    }

    /**
     * @return Filter[]
     */
    public function getFilters()
    {
        $this->init();
        return $this->filters;
    }
}