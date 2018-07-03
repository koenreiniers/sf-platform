<?php
namespace Raw\Pager;

class PagerRegistry
{
    /**
     * @var Pager[]
     */
    private $pagers = [];

    public function register(Pager $pager)
    {
        $this->pagers[] = $pager;
        return $this;
    }

    /**
     * @return Pager[]
     */
    public function getPagers()
    {
        return $this->pagers;
    }
}