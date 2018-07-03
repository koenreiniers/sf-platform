<?php
namespace Raw\Bundle\LuceneBundle\Lucene;

use Symfony\Component\Filesystem\Filesystem;
use ZendSearch\Lucene\Analysis\Analyzer\Analyzer;
use ZendSearch\Lucene\Index;
use ZendSearch\Lucene\Search\QueryParser;
use ZendSearch\Lucene\Storage\Directory\Filesystem as ZfFilesystem;

class IndexFactory
{
    private static function resolveOptions(array $options)
    {
        $defaults = [
            'path'                  => null,
            'analyzer'              => 'ZendSearch\Lucene\Analysis\Analyzer\Common\Text\CaseInsensitive',
            'max_buffered_docs'     => 10,
            'max_merge_docs'        => PHP_INT_MAX,
            'merge_factor'          => 10,
            'permissions'           => 0777,
            'auto_optimized'        => false,
            'query_parser_encoding' => '',
        ];
        return array_merge($defaults, $options);
    }

    public static function create(array $options)
    {
        $options = self::resolveOptions($options);
        $path = $options['path'];
        $create = !is_dir($path);
        $index = new Index($path, $create);

        $analyzerClass = $options['analyzer'];
        Analyzer::setDefault(new $analyzerClass());

        $index->setMaxBufferedDocs($options['max_buffered_docs']);
        $index->setMaxMergeDocs($options['max_merge_docs']);
        $index->setMergeFactor($options['merge_factor']);

        ZfFilesystem::setDefaultFilePermissions($options['permissions']);

        if ($options['auto_optimized']) {
            $index->optimize();
        }

        QueryParser::setDefaultEncoding($options['query_parser_encoding']);
        return $index;
    }
}