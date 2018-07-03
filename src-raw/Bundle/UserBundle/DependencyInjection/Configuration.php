<?php
namespace Raw\Bundle\UserBundle\DependencyInjection;

use Raw\Bundle\UserBundle\Entity\User;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('raw_user');

        $root
            ->children()
                ->scalarNode('user_class')->defaultValue(User::class)->end()
            ->end();

        return $builder;
    }
}