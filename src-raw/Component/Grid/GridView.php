<?php
namespace Raw\Component\Grid;

class GridView
{
    /**
     * @var array
     */
    public $vars = [];

    public function __construct(Grid $grid)
    {
        $this->vars = [
            'name' => $grid->getName(),
        ];
    }
}