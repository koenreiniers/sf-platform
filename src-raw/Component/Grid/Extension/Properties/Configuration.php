<?php
namespace Raw\Component\Grid\Extension\Properties;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\VarDumper\VarDumper;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('properties');

        $root
            ->useAttributeAsKey('key')
            ->variablePrototype()
            ->end();

//
//            ->arrayPrototype()
//                ->children()
//                    ->scalarNode('label')->defaultNull()->isRequired()->end()
//                    ->scalarNode('type')->defaultValue('navigate')->end()
//                    ->scalarNode('route')->defaultNull()->end()
//                    ->arrayNode('routeParams')->scalarPrototype()->end()->end()
//                    ->scalarNode('url')->defaultNull()->end()
//                ->end()
//            ->end()
//        ;

        return $builder;
    }
}