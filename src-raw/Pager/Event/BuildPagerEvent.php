<?php
namespace Raw\Pager\Event;

use Raw\Pager\Pager;
use Raw\Pager\PagerBuilder;
use Symfony\Component\EventDispatcher\Event;

class BuildPagerEvent extends Event
{
    /**
     * @var PagerBuilder
     */
    private $builder;

    /**
     * PagerBuilderEvent constructor.
     * @param PagerBuilder $builder
     */
    public function __construct(PagerBuilder $builder)
    {
        $this->builder = $builder;
    }


    /**
     * @return PagerBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

}