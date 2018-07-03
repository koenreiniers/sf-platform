<?php
namespace Raw\Bundle\LuceneBundle\Lucene\Config;

class ConfigBuilder extends Config
{
    /**
     * @var bool
     */
    private $locked = false;

    private function verifyNotLocked()
    {
        if($this->locked) {
            throw new \Exception('Config cannot be changed after it has been built');
        }
    }

    public function getConfig()
    {
        $config = clone $this;
        $config->locked = true;

        return $config;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->verifyNotLocked();
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->verifyNotLocked();
        $this->path = $path;
        return $this;
    }

    /**
     * @param string $analyzer
     * @return $this
     */
    public function setAnalyzer($analyzer)
    {
        $this->verifyNotLocked();
        $this->analyzer = $analyzer;
        return $this;
    }

    /**
     * @param int $maxBufferedDocs
     * @return $this
     */
    public function setMaxBufferedDocs($maxBufferedDocs)
    {
        $this->verifyNotLocked();
        $this->maxBufferedDocs = $maxBufferedDocs;
        return $this;
    }

    /**
     * @param int $maxMergeDocs
     * @return $this
     */
    public function setMaxMergeDocs($maxMergeDocs)
    {
        $this->verifyNotLocked();
        $this->maxMergeDocs = $maxMergeDocs;
        return $this;
    }

    /**
     * @param int $mergeFactor
     * @return $this
     */
    public function setMergeFactor($mergeFactor)
    {
        $this->verifyNotLocked();
        $this->mergeFactor = $mergeFactor;
        return $this;
    }

    /**
     * @param boolean $autoOptimized
     * @return $this
     */
    public function setAutoOptimized($autoOptimized)
    {
        $this->verifyNotLocked();
        $this->autoOptimized = $autoOptimized;
        return $this;
    }
}