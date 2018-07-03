<?php
namespace Raw\Search;

use Raw\Bundle\LuceneBundle\Lucene\IndexFactory;
use Raw\Search\SearchIndex;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Index;

interface SearchDriverInterface
{
    public function configureOptions(OptionsResolver $resolver);

    public function delete(SearchIndex $searchIndex, $id);

    public function addDocument(SearchIndex $searchIndex, Document $document);

    public function eraseIndex(SearchIndex $index);

    public function optimizeIndex(SearchIndex $searchIndex);

    public function find(SearchIndex $searchIndex, $query);

    public function getDocument(SearchIndex $searchIndex, $id);
}