<?php
namespace Raw\Component\Grid\Extension\Actions;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\VarDumper\VarDumper;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('actions');

        $root
            ->beforeNormalization()
                ->always(function($v){
                    foreach($v as $name => $actionConfig) {
                        $defaults = [
                            'label' => $name,
                        ];
                        $v[$name] = array_merge($defaults, $actionConfig);
                    }
                    return $v;
                })
            ->end()
            ->useAttributeAsKey('key')
            ->arrayPrototype()
                ->children()
                    ->scalarNode('label')->defaultNull()->isRequired()->end()
                    ->scalarNode('type')->defaultValue('navigate')->end()
                    ->scalarNode('route')->defaultNull()->end()
                    ->arrayNode('routeParams')->scalarPrototype()->end()->end()
                    ->scalarNode('url')->defaultNull()->end()
                    ->scalarNode('icon')->defaultNull()->end()
                    ->booleanNode('ajax')->defaultFalse()->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}