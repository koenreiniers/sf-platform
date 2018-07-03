<?php
namespace Raw\Search\Driver;

use Raw\Search\SearchDriverInterface;
use Raw\Search\SearchIndex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Stopwatch\Stopwatch;
use ZendSearch\Lucene\Document as LuceneDocument;
use ZendSearch\Lucene\Index;

class TraceableDriver implements SearchDriverInterface
{
    /**
     * @var SearchDriverInterface
     */
    private $driver;

    /**
     * @var Stopwatch
     */
    private $stopwatch;

    public function __construct(SearchDriverInterface $driver)
    {
        $this->driver = $driver;
        $this->stopwatch = new Stopwatch();
    }

    /**
     * @return Stopwatch
     */
    public function getStopwatch()
    {
        return $this->stopwatch;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $this->driver->configureOptions($resolver);
    }

    public function getDocument(SearchIndex $searchIndex, $id)
    {
        $this->stopwatch->start('getDocument');
        $out = $this->driver->getDocument($searchIndex, $id);
        $this->stopwatch->stop('getDocument');
        return $out;
    }

    public function delete(SearchIndex $searchIndex, $id)
    {
        $this->stopwatch->start('delete');
        $this->driver->delete($searchIndex, $id);
        $this->stopwatch->stop('delete');
    }

    public function eraseIndex(SearchIndex $index)
    {
        $this->stopwatch->start('eraseIndex');
        $this->driver->eraseIndex($index);
        $this->stopwatch->stop('eraseIndex');
    }

    public function optimizeIndex(SearchIndex $index)
    {
        $this->stopwatch->start('optimizeIndex');
        $this->driver->optimizeIndex($index);
        $this->stopwatch->stop('optimizeIndex');
    }

    public function find(SearchIndex $searchIndex, $query)
    {
        $this->stopwatch->start('find');
        $out = $this->driver->find($searchIndex, $query);
        $this->stopwatch->stop('find');
        return $out;
    }

    public function addDocument(SearchIndex $searchIndex, LuceneDocument $rawDocument)
    {
        $this->stopwatch->start('addDocument');
        $out = $this->driver->addDocument($searchIndex, $rawDocument);
        $this->stopwatch->stop('addDocument');
        return $out;
    }
}