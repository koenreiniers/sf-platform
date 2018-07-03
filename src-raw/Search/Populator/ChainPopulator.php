<?php
namespace Raw\Search\Populator;


use ZendSearch\Lucene\Index;

class ChainPopulator implements PopulatorInterface
{
    /**
     * @var PopulatorInterface[]
     */
    private $populators;

    /**
     * ChainPopulator constructor.
     * @param \Raw\Search\PopulatorInterface[] $populators
     */
    public function __construct(array $populators)
    {
        $this->populators = $populators;
    }

    public function populate(Index $index)
    {
        foreach($this->populators as $populator) {
            $populator->populate($index);
        }
    }
}