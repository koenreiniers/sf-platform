<?php
namespace Raw\Component\Batch\Item\Reader;

use Raw\Component\Batch\Item\ItemReaderInterface;
use Raw\Component\Batch\Step\StepExecutionAwareInterface;
use Raw\Component\Batch\StepExecution;

class InMemoryReader implements ItemReaderInterface, StepExecutionAwareInterface
{
    /**
     * @var array
     */
    private $items;

    /**
     * InMemoryReader constructor.
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function setStepExecution(StepExecution $stepExecution)
    {
        $params = $stepExecution->getJobParameters();
        $this->items = $params['items'];
    }

    public function read()
    {
        $item = current($this->items);

        if($item === false) {
            return null;
        }

        next($this->items);

        return $item;
    }
}