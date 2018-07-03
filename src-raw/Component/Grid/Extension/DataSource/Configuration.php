<?php
namespace Raw\Component\Grid\Extension\DataSource;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('data_source');

        $root
            ->children()
                ->scalarNode('type')->defaultValue('orm')->isRequired()->end()
                ->variableNode('options')->defaultValue([])->end()
                ->arrayNode('bind_parameters')
                    ->scalarPrototype()->end()
                ->end()
            ->end();

        return $builder;
    }
}