<?php
namespace Raw\Component\Grid\Event;

use Raw\Component\Grid\DataSource\Data;
use Raw\Component\Grid\Grid;
use Symfony\Component\EventDispatcher\Event;

class GridDataEvent extends GridEvent
{
    /**
     * @var Data
     */
    protected $data;

    /**
     * GridEvent constructor.
     * @param Grid $grid
     */
    public function __construct(Grid $grid, Data $data)
    {
        parent::__construct($grid);
        $this->data = $data;
    }

    /**
     * @return Data
     */
    public function getData()
    {
        return $this->data;
    }
}