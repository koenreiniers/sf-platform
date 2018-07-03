<?php
namespace Raw\Component\Batch\Item;

interface InitializableInterface
{
    /**
     * @return void
     */
    public function initialize();
}