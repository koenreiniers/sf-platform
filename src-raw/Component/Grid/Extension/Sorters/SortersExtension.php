<?php
namespace Raw\Component\Grid\Extension\Sorters;

use Raw\Component\Grid\Event\GridDataEvent;
use Raw\Component\Grid\Event\GridEvent;
use Raw\Component\Grid\GridBuilder;
use Raw\Component\Grid\GridEvents;
use Raw\Component\Grid\GridView;
use Raw\Component\Grid\Mapping\GridMetadataBuilder;
use Raw\Component\Grid\Grid;
use Raw\Component\Grid\GridExtension;
use Symfony\Component\VarDumper\VarDumper;

class SortersExtension extends GridExtension
{

    public function build(GridBuilder $builder)
    {
        $builder->addEventListener(GridEvents::PRE_GET_DATA, function(GridEvent $event){

            $grid = $event->getGrid();

            $sorters = $grid->getProperty('sorters');

            // Sort
            $defaults = [
                'by' => null,
                'dir' => 'ASC',
            ];
            $sort = array_merge($defaults, $grid->getParameter('sort', []));
            if($sort['by'] !== null) {
                if(isset($sorters[$sort['by']])) {
                    $sorter = $sorters[$sort['by']];
                    $grid->getDataSource()->orderBy($sorter['field'], $sort['dir']);
                }
            }
        });
    }

    public function finishView(GridView $view, Grid $grid)
    {
        $sorters = $grid->getProperty('sorters');
        foreach($view->vars['columns'] as $columnName => $columnView) {
            if(isset($sorters[$columnName])) {
                $view->vars['columns'][$columnName]['sortable'] = true;
            }
        }
    }

    public function load(array $configs, GridMetadataBuilder $builder)
    {
        $sorters = $this->processConfiguration(new Configuration(), $configs);

        $builder->setProperty('sorters', $sorters);
    }

}