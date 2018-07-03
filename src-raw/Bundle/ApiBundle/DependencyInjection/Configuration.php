<?php
namespace Raw\Bundle\ApiBundle\DependencyInjection;


use Raw\Bundle\LuceneBundle\Lucene\Config\Config;
use Raw\Bundle\LuceneBundle\Lucene\LuceneRegistry;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $root = $treeBuilder->root('raw_api');

        return $treeBuilder;
    }
}
