<?php
namespace Raw\Component\Grid\Extension\Filters;

use Raw\Component\Grid\Event\GridEvent;
use Raw\Component\Grid\GridBuilder;
use Raw\Component\Grid\GridEvents;
use Raw\Component\Grid\GridView;
use Raw\Component\Grid\Mapping\GridMetadataBuilder;
use Raw\Filter\Context\Loader\ArrayLoader;
use Raw\Filter\Filter;
use Raw\Filter\FiltererFactory;
use Raw\Filter\Storage\InMemoryStorage;
use Raw\Component\Grid\Grid;
use Raw\Component\Grid\GridExtension;
use Symfony\Component\VarDumper\VarDumper;

class FiltersExtension extends GridExtension
{
    /**
     * @var FiltererFactory
     */
    private $filtererFactory;

    /**
     * FiltersExtension constructor.
     * @param FiltererFactory $filtererFactory
     */
    public function __construct(FiltererFactory $filtererFactory)
    {
        $this->filtererFactory = $filtererFactory;
    }

    public function load(array $configs, GridMetadataBuilder $builder)
    {
        $filters = $this->processConfiguration(new Configuration(), $configs);

        $builder->setProperty('filters', $filters);
    }

    public function build(GridBuilder $builder)
    {

        $filtererBuilder = $this->filtererFactory->createBuilder()
            ->setStorage(new InMemoryStorage())
            ->setAdapter(new \Raw\Filter\Adapter\DataSourceAdapter($builder->getDataSource()))
        ;
        $loader = new ArrayLoader($filtererBuilder);
        $loader->load([
            'filters' => $builder->getProperty('filters'),
        ]);
        $filterer = $filtererBuilder->getFilterer();

        $builder->addEventListener(GridEvents::PRE_GET_DATA, function(GridEvent $event) use($filterer) {

            $grid = $event->getGrid();

            $filterer->clear();
            $filters = $grid->getParameter('filters', []);
            foreach($filters as $filter) {
                $filter = array_merge([
                    'name' => null,
                    'operator' => null,
                    'data' => [],
                ], $filter);
                $filterer->addFilter(new Filter($filter['name'], $filter['operator'], $filter['data']));
            }
            $filterer->apply();

        });
    }

    public function buildView(GridView $view, Grid $grid)
    {
        $filtererBuilder = $this->filtererFactory->createBuilder()
            ->setStorage(new InMemoryStorage())
            ->setAdapter(new \Raw\Filter\Adapter\DataSourceAdapter($grid->getDataSource()))
        ;
        $loader = new ArrayLoader($filtererBuilder);
        $loader->load([
            'filters' => $grid->getProperty('filters'),
        ]);
        $filterer = $filtererBuilder->getFilterer();

        $filterDefinitions = $filterer->getContext()->getDefinitions();

        $filters = [];
        $hiddenProperties = ['field'];

        foreach($filterDefinitions as $name => $filterDefinition) {
            $filter= $filterDefinition->getOptions();

            foreach($hiddenProperties as $hiddenProperty) {
                unset($filter[$hiddenProperty]);
            }
            $filters[$name] = $filter;

        }

        #VarDumper::dump($filters);
        $view->vars['filters'] = $filters;
        return;



        $filters = [];
        foreach($grid->getProperty('filters') as $name => $filter) {
            foreach($hiddenProperties as $hiddenProperty) {
                unset($filter[$hiddenProperty]);
            }
            $filters[$name] = $filter;
        }

        $view->vars['filters'] = $filters;

        #VarDumper::dump($filters);die;
    }
}