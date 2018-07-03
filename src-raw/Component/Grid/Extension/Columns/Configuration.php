<?php
namespace Raw\Component\Grid\Extension\Columns;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\VarDumper\VarDumper;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('columns');

        $root
            ->beforeNormalization()
                ->always(function($v){
                    foreach($v as $name => $actionConfig) {
                        $defaults = [
                            'label' => $name,
                            'property' => $name,
                            'type' => 'string',
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
                    ->scalarNode('property')->defaultNull()->isRequired()->end()
                    ->scalarNode('type')->defaultValue('string')->end()
                    ->scalarNode('formatter')->defaultNull()->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}