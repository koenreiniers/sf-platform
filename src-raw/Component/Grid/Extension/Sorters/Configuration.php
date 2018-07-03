<?php
namespace Raw\Component\Grid\Extension\Sorters;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('sorters');

        $root
            ->useAttributeAsKey('name')
            ->arrayPrototype()
                ->children()
                    ->scalarNode('field')->isRequired()->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}