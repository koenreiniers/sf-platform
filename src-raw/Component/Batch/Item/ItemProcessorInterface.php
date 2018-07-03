<?php
namespace Raw\Component\Batch\Item;

interface ItemProcessorInterface
{
    /**
     * @param mixed $item
     *
     * @return mixed
     */
    public function process($item);
}