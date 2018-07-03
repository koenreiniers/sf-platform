<?php
namespace Raw\Bundle\LuceneBundle\DependencyInjection;


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

        $root = $treeBuilder->root('raw_lucene');

        $root
            ->children()
                ->arrayNode('indexes')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('path')
                                ->isRequired()
                            ->end()
                            ->scalarNode('analyzer')
                                ->defaultValue(Config::DEFAULT_ANALYZER)
                            ->end()
                            ->scalarNode('max_buffered_docs')
                                ->defaultValue(Config::DEFAULT_MAX_BUFFERED_DOCS)
                            ->end()
                            ->scalarNode('max_merge_docs')
                                ->defaultValue(Config::DEFAULT_MAX_MERGE_DOCS)
                            ->end()
                            ->scalarNode('merge_factor')
                                ->defaultValue(Config::DEFAULT_MERGE_FACTOR)
                            ->end()
                            ->scalarNode('permissions')
                                ->defaultValue(LuceneRegistry::DEFAULT_PERMISSIONS)
                            ->end()
                            ->scalarNode('auto_optimized')
                                ->defaultValue(Config::DEFAULT_AUTO_OPTIMIZED)
                            ->end()
                            ->scalarNode('query_parser_encoding')
                                ->defaultValue(LuceneRegistry::DEFAULT_QUERY_PARSER_ENCODING)
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
