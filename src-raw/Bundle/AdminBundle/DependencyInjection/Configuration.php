<?php
namespace Raw\Bundle\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('raw_admin');

        $root
            ->children()
                ->arrayNode('templates')
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $builder;
    }
}