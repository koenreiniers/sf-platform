<?php
namespace Raw\Component\Batch\Step;

use Raw\Component\Batch\Item\InitializableInterface;
use Raw\Component\Batch\Item\ItemProcessorInterface;
use Raw\Component\Batch\Item\ItemReaderInterface;
use Raw\Component\Batch\Item\ItemWriterInterface;
use Raw\Component\Batch\StepExecution;
use Raw\Component\Batch\StepInterface;

class ItemStep implements StepInterface
{
    /**
     * @var ItemReaderInterface
     */
    private $reader;

    /**
     * @var ItemProcessorInterface
     */
    private $processor;

    /**
     * @var ItemWriterInterface
     */
    private $writer;

    /**
     * @var string
     */
    private $name;

    /**
     * ItemStep constructor.
     * @param string $name
     * @param ItemReaderInterface $reader
     * @param ItemProcessorInterface $processor
     * @param ItemWriterInterface $writer
     */
    public function __construct($name, ItemReaderInterface $reader, ItemProcessorInterface $processor, ItemWriterInterface $writer)
    {
        $this->name = $name;
        $this->reader = $reader;
        $this->processor = $processor;
        $this->writer = $writer;
    }

    public function execute(StepExecution $stepExecution)
    {


        $elements = [$this->reader, $this->processor, $this->writer];
        foreach($elements as $element) {
            if($element instanceof StepExecutionAwareInterface) {
                $element->setStepExecution($stepExecution);
            }
            if($element instanceof InitializableInterface) {
                $element->initialize();
            }
        }

        $processedItems = [];
        while(($readItem = $this->reader->read()) !== null) {
            $stepExecution->incrementSummaryInfo('read_items');
            $processedItem = $this->processor->process($readItem);
            $stepExecution->incrementSummaryInfo('processed_items');
            $processedItems[] = $processedItem;

        }

        $this->writer->write($processedItems);
    }

    public function getName()
    {
        return $this->name;
    }
}