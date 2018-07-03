<?php
namespace Raw\Component\Grid\Event;

use Raw\Component\Grid\Grid;
use Symfony\Component\EventDispatcher\Event;

class GridEvent extends Event
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * GridEvent constructor.
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * @return Grid
     */
    public function getGrid()
    {
        return $this->grid;
    }
}