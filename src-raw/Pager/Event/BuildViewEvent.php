<?php
namespace Raw\Pager\Event;

use Raw\Pager\Pager;
use Raw\Pager\PagerView;
use Symfony\Component\EventDispatcher\Event;

class BuildViewEvent extends PagerEvent
{
    /**
     * @var PagerView
     */
    private $view;

    /**
     * PagerViewEvent constructor.
     * @param PagerView $view
     */
    public function __construct(Pager $pager, PagerView $view)
    {
        $this->view = $view;
        parent::__construct($pager);
    }

    /**
     * @return PagerView
     */
    public function getView()
    {
        return $this->view;
    }

}