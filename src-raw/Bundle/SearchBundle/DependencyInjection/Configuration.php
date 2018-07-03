<?php
namespace Raw\Bundle\SearchBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $root = $treeBuilder->root('raw_search');

        $root
            ->children()
                ->arrayNode('indexes')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('driver')->isRequired()->end()
                            ->arrayNode('driver_options')
                                ->prototype('variable')
                                ->end()
                            ->end()
                            ->booleanNode('realtime')->defaultFalse()->end()
                            ->scalarNode('default_hydrator')
                                ->defaultNull()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
