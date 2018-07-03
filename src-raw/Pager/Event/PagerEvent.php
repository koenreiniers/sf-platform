<?php
namespace Raw\Pager\Event;

use Raw\Pager\Pager;
use Symfony\Component\EventDispatcher\Event;

class PagerEvent extends Event
{
    /**
     * @var Pager
     */
    private $pager;

    /**
     * PagerEvent constructor.
     * @param Pager $pager
     */
    public function __construct(Pager $pager)
    {
        $this->pager = $pager;
    }


    /**
     * @return Pager
     */
    public function getPager()
    {
        return $this->pager;
    }

}