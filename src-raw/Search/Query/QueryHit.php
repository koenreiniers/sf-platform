<?php
namespace Raw\Search\Query;

use Raw\Search\SearchIndex;

class QueryHit
{
    /**
     * @var SearchIndex
     */
    private $index;

    private $id;

    private $documentId;

    private $score;

    private $document;

    /**
     * QueryHit constructor.
     * @param int $id
     * @param int $documentId
     * @param int $score
     */
    public function __construct(SearchIndex $index, $id, $documentId, $score)
    {
        $this->index = $index;
        $this->id = $id;
        $this->documentId = $documentId;
        $this->score = $score;
    }

    /**
     * @return SearchIndex
     */
    public function getIndex()
    {
        return $this->index;
    }

    public function getDocument()
    {
        if($this->document === null) {
            $this->document = $this->index->getDocument($this->documentId);
        }
        return $this->document;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDocumentId()
    {
        return $this->documentId;
    }

    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }
}