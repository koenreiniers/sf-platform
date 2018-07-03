<?php
namespace Raw\Search\Populator;

use ZendSearch\Lucene\Index;

interface PopulatorInterface
{
    /**
     * @param Index $index
     */
    public function populate(Index $index);
}