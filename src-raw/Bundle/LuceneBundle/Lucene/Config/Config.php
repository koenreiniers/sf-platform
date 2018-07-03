<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Config;

class Config
{
    const DEFAULT_ANALYZER = 'ZendSearch\Lucene\Analysis\Analyzer\Common\Text\CaseInsensitive';

    /** @const integer */
    const DEFAULT_MAX_BUFFERED_DOCS = 10;

    /** @const integer */
    const DEFAULT_MAX_MERGE_DOCS = PHP_INT_MAX;

    /** @const integer */
    const DEFAULT_MERGE_FACTOR = 10;

    /** @const boolean */
    const DEFAULT_AUTO_OPTIMIZED = false;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $analyzer = self::DEFAULT_ANALYZER;

    /**
     * @var int
     */
    protected $maxBufferedDocs = self::DEFAULT_MAX_BUFFERED_DOCS;

    /**
     * @var int
     */
    protected $maxMergeDocs = self::DEFAULT_MAX_MERGE_DOCS;

    /**
     * @var int
     */
    protected $mergeFactor = self::DEFAULT_MERGE_FACTOR;

    /**
     * @var bool
     */
    protected $autoOptimized = self::DEFAULT_AUTO_OPTIMIZED;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getAnalyzer()
    {
        return $this->analyzer;
    }

    /**
     * @return int
     */
    public function getMaxBufferedDocs()
    {
        return $this->maxBufferedDocs;
    }

    /**
     * @return int
     */
    public function getMaxMergeDocs()
    {
        return $this->maxMergeDocs;
    }

    /**
     * @return int
     */
    public function getMergeFactor()
    {
        return $this->mergeFactor;
    }

    /**
     * @return boolean
     */
    public function isAutoOptimized()
    {
        return $this->autoOptimized;
    }
}