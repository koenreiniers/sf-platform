<?php
namespace Raw\Component\Batch\Item;


interface ItemWriterInterface
{
    /**
     * @param array|\Traversable $items
     */
    public function write($items);
}