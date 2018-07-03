<?php
namespace Raw\Component\Grid\Extension\DataSource;

use Raw\Component\Grid\DataSource\DataSource;
use Raw\Component\Grid\DataSource\DataSourceFactoryInterface;
use Raw\Component\Grid\Event\GridEvent;
use Raw\Component\Grid\GridBuilder;
use Raw\Component\Grid\GridEvents;
use Raw\Component\Grid\GridView;
use Raw\Component\Grid\Mapping\GridMetadataBuilder;
use Raw\Component\Grid\Grid;
use Raw\Component\Grid\GridExtension;
use Symfony\Component\Routing\RouterInterface;

class DataSourceExtension extends GridExtension
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var DataSourceFactoryInterface
     */
    private $dataSourceFactory;

    /**
     * DataSourceExtension constructor.
     * @param RouterInterface $router
     * @param DataSourceFactoryInterface $dataSourceFactory
     */
    public function __construct(RouterInterface $router, DataSourceFactoryInterface $dataSourceFactory)
    {
        $this->router = $router;
        $this->dataSourceFactory = $dataSourceFactory;
    }

    public function load(array $configs, GridMetadataBuilder $builder)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $type = $config['type'];
        $dataSourceOptions = $config['options'];

        if(!$this->dataSourceFactory->supports($type, $dataSourceOptions)) {
            throw new \Exception('Data source of type "%s" is not supported', $type);
        }

        $builder->setProperty('data_source.type', $type);
        $builder->setProperty('data_source.options', $dataSourceOptions);
        $builder->setProperty('data_source.bind_parameters', $config['bind_parameters']);
    }

    public function buildView(GridView $view, Grid $grid)
    {
        $view->vars['source'] = [
            'type' => 'url',
            'url' => $this->router->generate('raw_grid.grid.data', [
                'gridName' => $grid->getName(),
            ]),
        ];
    }

    public function build(GridBuilder $builder)
    {
        $dataSourceAdapter = $this->dataSourceFactory->create($builder->getProperty('data_source.type'), $builder->getProperty('data_source.options'));
        $dataSource = new DataSource($dataSourceAdapter);
        $builder->setDataSource($dataSource);

        $builder->addEventListener(GridEvents::PRE_GET_DATA, function(GridEvent $event){

            $grid = $event->getGrid();
            $parameters = $grid->getParameter('parameters', []);
            foreach($grid->getProperty('data_source.bind_parameters') as $bindTo) {
                $parameterName = $bindTo;
                $value = isset($parameters[$parameterName]) ? $parameters[$parameterName] : null;
                $grid->getDataSource()->setParameter($bindTo, $value);
            }
        });

    }
}