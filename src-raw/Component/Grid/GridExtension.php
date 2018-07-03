<?php
namespace Raw\Component\Grid;

use Doctrine\ORM\QueryBuilder;
use Raw\Component\Grid\Mapping\GridMetadataBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

class GridExtension
{
    public function buildView(GridView $view, Grid $grid)
    {

    }

    public function finishView(GridView $view, Grid $grid)
    {

    }

    public function load(array $configs, GridMetadataBuilder $builder)
    {

    }

    public function build(GridBuilder $builder)
    {

    }

    public function getAlias()
    {
        $className = get_class($this);
        if (substr($className, -9) != 'Extension') {
            throw new \Exception('This extension does not follow the naming convention; you must overwrite the getAlias() method.');
        }
        $classBaseName = substr(strrchr($className, '\\'), 1, -9);

        return Container::underscore($classBaseName);
    }

    protected function processConfiguration(ConfigurationInterface $configuration, array $configs)
    {
        $processor = new Processor();
        return $processor->processConfiguration($configuration, $configs);
    }
}