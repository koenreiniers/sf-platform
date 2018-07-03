<?php
namespace Raw\Component\Batch\Item;

interface ItemReaderInterface
{
    /**
     * @return mixed|null
     */
    public function read();
}