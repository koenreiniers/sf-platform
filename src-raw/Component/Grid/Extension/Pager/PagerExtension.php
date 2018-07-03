<?php
namespace Raw\Component\Grid\Extension\Pager;

use Raw\Component\Grid\Event\GridEvent;
use Raw\Component\Grid\GridBuilder;
use Raw\Component\Grid\GridEvents;
use Raw\Component\Grid\GridExtension;

class PagerExtension extends GridExtension
{
    public function build(GridBuilder $builder)
    {
        $builder->addEventListener(GridEvents::PRE_GET_DATA, function(GridEvent $event){

            $grid = $event->getGrid();

            $pagination = array_merge([
                'page' => 1,
                'itemsPerPage' => 1,
            ], $grid->getParameter('pagination', []));

            $grid->getDataSource()
                ->setPage($pagination['page'])
                ->setPageSize($pagination['itemsPerPage']);
        });
    }
}