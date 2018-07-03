<?php
namespace Raw\Search\Driver;

use Raw\Bundle\LuceneBundle\Lucene\IndexFactory;
use Raw\Search\SearchDriverInterface;
use Raw\Search\SearchIndex;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ZendSearch\Lucene\Document as LuceneDocument;
use ZendSearch\Lucene\Index;
use ZendSearch\Lucene\Search\QueryHit as LuceneQueryHit;
use Raw\Search\Query\QueryHit as RawQueryHit;

class LuceneDriver implements SearchDriverInterface
{
    /**
     * @var Index[]
     */
    private $indexes = [];

    /**
     * @var Filesystem
     */
    private $fs;

    public function __construct()
    {
        $this->indexes = [];
        $this->fs = new Filesystem();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['path']);
        $resolver->setDefaults([
            'analyzer'              => 'ZendSearch\Lucene\Analysis\Analyzer\Common\Text\CaseInsensitive',
            'max_buffered_docs'     => 10,
            'max_merge_docs'        => PHP_INT_MAX,
            'merge_factor'          => 10,
            'permissions'           => 0777,
            'auto_optimized'        => false,
            'query_parser_encoding' => '',
        ]);
    }

    protected function getIndex(SearchIndex $searchIndex)
    {
        if(!isset($this->indexes[$searchIndex->getName()])) {
            $this->indexes[$searchIndex->getName()] = IndexFactory::create($searchIndex->getConfig()->getDriverOptions());
        }
        return $this->indexes[$searchIndex->getName()];
    }

    public function getDocument(SearchIndex $searchIndex, $id)
    {
        $luceneDoc = $this->getIndex($searchIndex)->getDocument($id);
        return $this->luceneDocToRawDoc($luceneDoc);
    }

    public function delete(SearchIndex $searchIndex, $id)
    {
        $this->getIndex($searchIndex)->delete($id);
    }

    public function eraseIndex(SearchIndex $index)
    {
        $options = $index->getConfig()->getDriverOptions();
        $this->fs->remove($options['path']);
    }

    public function optimizeIndex(SearchIndex $searchIndex)
    {
        $this->getIndex($searchIndex)->optimize();
    }

    private function luceneHitToRawHit(SearchIndex $index, LuceneQueryHit $luceneHit)
    {
        $rawHit = new RawQueryHit($index, $luceneHit->id, $luceneHit->document_id, $luceneHit->score);
        return $rawHit;
    }

    private function rawhitToLuceneHit($rawHit)
    {
        return $rawHit;
    }

    private function luceneDocToRawDoc(LuceneDocument $luceneDocument)
    {
        return $luceneDocument;
    }

    private function rawDocToLuceneDoc($rawDocument)
    {
        return $rawDocument;
    }

    public function find(SearchIndex $searchIndex, $query)
    {

        $hits = $this->getIndex($searchIndex)->find($query);
        $converted = [];
        foreach($hits as $hit) {
            $converted[] = $this->luceneHitToRawHit($searchIndex, $hit);
        }
        return $converted;
    }

    public function addDocument(SearchIndex $searchIndex, LuceneDocument $rawDocument)
    {
        $document = $this->rawDocToLuceneDoc($rawDocument);
        return $this->getIndex($searchIndex)->addDocument($document);
    }
}