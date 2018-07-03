<?php
namespace Raw\Bundle\LuceneBundle\Lucene;

use Raw\Bundle\LuceneBundle\Lucene\Config\Config;
use Raw\Bundle\LuceneBundle\Lucene\Config\ConfigBuilder;
use Raw\Bundle\LuceneBundle\Lucene\Document\DocumentBuilder;
use Raw\Bundle\LuceneBundle\Lucene\Document\DocumentFactory;
use Raw\Bundle\LuceneBundle\Lucene\Expression\ExpressionBuilder;
use Symfony\Component\Filesystem\Filesystem;
use ZendSearch\Lucene\Analysis\Analyzer\Analyzer;
use ZendSearch\Lucene\Index;
use ZendSearch\Lucene\Search\QueryParser;
use ZendSearch\Lucene\SearchIndexInterface;
use ZendSearch\Lucene\Storage\Directory\Filesystem as ZfFilesystem;

class LuceneRegistry
{
    /** @const integer */
    const DEFAULT_PERMISSIONS = 0777;

    /** @const boolean */
    const DEFAULT_AUTO_OPTIMIZED = false;

    /** @const string */
    const DEFAULT_QUERY_PARSER_ENCODING = '';

    /**
     * @var Config[]
     */
    private $configs = [];

    /**
     * @var Index[]
     */
    private $indexes = [];

    /**
     * @var DocumentFactory
     */
    private $documentFactory;

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var ExpressionBuilder
     */
    private $expressionBuilder;

    public function __construct(array $configs)
    {
        $this->documentFactory = new DocumentFactory();
        $this->configs = [];
        foreach($configs as $name => $config) {
            $this->configs[$name] = $this->resolveConfig($name, $config);
        }
        $this->fs = new Filesystem();
    }

    /**
     * @return ExpressionBuilder
     */
    public function getExpressionBuilder()
    {
        if($this->expressionBuilder === null) {
            $this->expressionBuilder = new ExpressionBuilder();
        }
        return $this->expressionBuilder;
    }

    /**
     * @return \ZendSearch\Lucene\Document
     */
    public function createDocument()
    {
        return $this->documentFactory->create();
    }

    /**
     * @return DocumentBuilder
     */
    public function createDocumentBuilder()
    {
        return $this->documentFactory->createBuilder();
    }

    private function createConfigBuilder()
    {
        return new ConfigBuilder();
    }

    private function resolveConfig($name, array $config)
    {
        if(!isset($config['path'])) {
            throw new \Exception('Index path should be configured');
        }
        $defaults = [
            'analyzer'              => Config::DEFAULT_ANALYZER,
            'max_buffered_docs'     => Config::DEFAULT_MAX_BUFFERED_DOCS,
            'max_merge_docs'        => Config::DEFAULT_MAX_MERGE_DOCS,
            'merge_factor'          => Config::DEFAULT_MERGE_FACTOR,
            'auto_optimized'        => Config::DEFAULT_AUTO_OPTIMIZED,
        ];
        $options = array_merge($defaults, $config);

        $builder = $this->createConfigBuilder()
            ->setName($name)
            ->setPath($options['path'])
            ->setAnalyzer($options['analyzer'])
            ->setMaxBufferedDocs($options['max_buffered_docs'])
            ->setMaxMergeDocs($options['max_merge_docs'])
            ->setMergeFactor($options['merge_factor'])
            ->setAutoOptimized($options['auto_optimized']);
        return $builder->getConfig();

    }

    /**
     * @param string $name
     * @return Index
     * @throws \Exception
     */
    public function getIndex($name)
    {
        if(isset($this->indexes[$name])) {
            return $this->indexes[$name];
        }
        return $this->indexes[$name] = $this->createIndex($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasIndex($name)
    {
        return $this->hasConfig($name);
    }

    /**
     * @param string $name
     */
    public function eraseIndex($name)
    {
        $config = $this->getConfig($name);
        $this->fs->remove($config->getPath());
    }

    /**
     * @param string $name
     * @return bool
     */
    private function hasConfig($name)
    {
        return isset($this->configs[$name]);
    }

    /**
     * @param string $name
     * @return Config
     * @throws \Exception
     */
    private function getConfig($name)
    {
        if(!$this->hasConfig($name)) {
            throw new \Exception(sprintf('No config found for index "%s"', $name));
        }
        return $this->configs[$name];
    }


    private function applyConfig(SearchIndexInterface $index, Config $config)
    {
        $analyzerClass = $config->getAnalyzer();
        Analyzer::setDefault(new $analyzerClass());

        $index->setMaxBufferedDocs($config->getMaxBufferedDocs());
        $index->setMaxMergeDocs($config->getMaxMergeDocs());
        $index->setMergeFactor($config->getMergeFactor());

        ZfFilesystem::setDefaultFilePermissions(self::DEFAULT_PERMISSIONS);

        if ($config->isAutoOptimized()) {
            $index->optimize();
        }

        QueryParser::setDefaultEncoding(self::DEFAULT_QUERY_PARSER_ENCODING);
    }

    private function createIndex($name)
    {
        $config = $this->getConfig($name);
        $directory = $config->getPath();
        $create = !$this->containsIndex($directory);
        $index = new Index($directory, $create);
        $this->applyConfig($index, $config);
        return $index;
    }

    /**
     * Checks if a lucene index path exists.
     *
     * @param string $path The lucene index path.
     *
     * @return boolean TRUE if the lucene index path exists else FALSE.
     */
    private function containsIndex($path)
    {
        return file_exists($path) && is_readable($path) && ($resources = scandir($path)) && (count($resources) > 2);
    }

}