<?php
namespace Raw\Component\Batch\Item\Reader;

use Raw\Component\Batch\Item\ItemReaderInterface;
use Raw\Component\Batch\Step\StepExecutionAwareInterface;
use Raw\Component\Batch\StepExecution;

abstract class BasicItemReader implements ItemReaderInterface
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @return array
     */
    abstract protected function loadItems();

    public function read()
    {
        if($this->items === null) {
            $this->items = $this->loadItems();
        }
        $item = current($this->items);

        if($item === false) {
            return null;
        }

        next($this->items);

        return $item;
    }
}