<?php
namespace Raw\Component\Grid\Extension\Filters;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\VarDumper\VarDumper;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('filters');

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
                    ->scalarNode('type')->defaultValue('string')->end()
                    ->scalarNode('field')->defaultNull()->end()
                    ->arrayNode('choices')->scalarPrototype()->end()->end()
                    ->scalarNode('class')->defaultNull()->end()
                    ->scalarNode('choice_label')->defaultNull()->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}