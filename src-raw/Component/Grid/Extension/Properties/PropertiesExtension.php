<?php
namespace Raw\Component\Grid\Extension\Properties;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Raw\Component\Batch\Job\Launcher\SymfonyLauncher;
use Raw\Bundle\BatchBundle\Entity\JobInstance;
use Raw\Component\Grid\Event\GridDataEvent;
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
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\VarDumper\VarDumper;

class PropertiesExtension extends GridExtension
{


    public function buildView(GridView $view, Grid $grid)
    {
        if($grid->hasProperty('identifier')) {
            $view->vars['identifier'] = $grid->getProperty('identifier');
        }
    }

    public function build(GridBuilder $builder)
    {
        $builder->addEventListener(GridEvents::POST_GET_DATA, function(GridDataEvent $event){
            global $kernel;

            $container = $kernel->getContainer();

            $router = $container->get('router');

            $grid = $event->getGrid();
            $data = $event->getData();

            if(!$grid->hasProperty('computed_data')) {
                return;
            }




            foreach($data as $item) {

                foreach($grid->getProperty('computed_data') as $propertyName => $propertyOptions) {
                    if(!isset($propertyOptions['type'])) {
                        continue;
                    }
                    switch($propertyOptions['type']) {
                        case 'url':
                            $route = $propertyOptions['route'];
                            $routeArgs = [];
                            foreach($propertyOptions['routeParams'] as $target => $source) {
                                $value = $source;
                                if(strpos($source, ':') === 0) {
                                    $value = $item[substr($source, 1)];
                                }
                                $routeArgs[$target] = $value;
                            }
                            $propertyValue = $router->generate($route, $routeArgs, UrlGeneratorInterface::ABSOLUTE_URL);
                            break;
                    }
                    $item[$propertyName] = $propertyValue;

                }

            }


        });
    }

    public function load(array $configs, GridMetadataBuilder $builder)
    {
        $properties = $this->processConfiguration(new Configuration(), $configs);


        foreach($properties as $name => $value) {
            $builder->setProperty($name, $value);
        }
    }
}