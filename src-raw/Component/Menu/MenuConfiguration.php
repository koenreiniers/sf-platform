<?php
namespace Raw\Component\Menu;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class MenuConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('menu');

        $root
            ->children()
                ->scalarNode('label')->end()
                ->scalarNode('import')->defaultNull()->end()
                ->scalarNode('route')->defaultNull()->end()
                ->variableNode('routeParameters')->defaultValue([])->end()
                ->scalarNode('uri')->defaultNull()->end()
                ->arrayNode('extras')
                    ->variablePrototype()->end()
                ->end()
                ->arrayNode('childrenAttributes')
                    ->variablePrototype()->end()
                ->end()
                ->append($this->createChildrenNode())
            ->end();

        return $builder;
    }

    private function createChildrenNode($depth = 0)
    {
        $builder = new TreeBuilder();

        $root = $builder->root('children');

        if($depth > 6) {
            return $root;
        }

        $root
            ->useAttributeAsKey('name')
            ->arrayPrototype()
            ->children()
                ->scalarNode('label')->end()
                ->scalarNode('import')->defaultNull()->end()
                ->scalarNode('route')->defaultNull()->end()
                ->variableNode('routeParameters')->defaultValue([])->end()
                ->scalarNode('uri')->defaultNull()->end()
                ->arrayNode('extras')
                    ->variablePrototype()->end()
                ->end()
                ->arrayNode('childrenAttributes')
                    ->variablePrototype()->end()
                ->end()
                ->append($this->createChildrenNode(++$depth))
            ->end()
        ->end();

        return $root;
    }
}