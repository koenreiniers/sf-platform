<?php
namespace Raw\Component\Grid\DataSource;

class Data implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $results;

    /**
     * @var int
     */
    private $totalSize;

    /**
     * @var ResultRecord[]
     */
    private $records;

    /**
     * Data constructor.
     * @param array $results
     * @param int $totalSize
     */
    public function __construct(array $results, $totalSize)
    {
        $this->results = $results;
        $records = [];
        foreach($results as $result) {
            $records[] = new ResultRecord($result);
        }
        $this->records = $records;
        $this->totalSize = $totalSize;
    }

    public function toArray()
    {
        $result = [];
        foreach($this->records as $record) {
            $result[] = $record->toArray();
        }
        return $result;
    }

    /**
     * @return int
     */
    public function getTotalSize()
    {
        return $this->totalSize;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->records);
    }
}