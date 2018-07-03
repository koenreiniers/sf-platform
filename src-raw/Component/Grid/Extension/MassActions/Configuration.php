<?php
namespace Raw\Component\Grid\Extension\MassActions;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\VarDumper\VarDumper;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('mass_actions');

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
                    ->scalarNode('type')->isRequired()->end()
                    ->scalarNode('label')->defaultNull()->isRequired()->end()
                    ->variableNode('options')->defaultValue([])->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}