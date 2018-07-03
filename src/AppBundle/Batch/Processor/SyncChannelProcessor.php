<?php
namespace AppBundle\Batch\Processor;

use AppBundle\Entity\Channel;
use Platform\PlatformHelper;
use Raw\Bundle\BatchBundle\Entity\Warning;
use Raw\Component\Batch\Item\ItemProcessorInterface;
use Raw\Component\Batch\Step\StepExecutionAwareInterface;
use Raw\Component\Batch\Step\StepExecutionAwareTrait;

class SyncChannelProcessor implements ItemProcessorInterface, StepExecutionAwareInterface
{
    use StepExecutionAwareTrait;

    /**
     * @var PlatformHelper
     */
    private $platformHelper;

    /**
     * CompleteSyncProcessor constructor.
     * @param PlatformHelper $platformHelper
     */
    public function __construct(PlatformHelper $platformHelper)
    {
        $this->platformHelper = $platformHelper;
    }

    public function process($item)
    {
        $this->stepExecution->addWarning((new Warning())->setStepExecution($this->stepExecution)->setMessage('Test warning')->setItem(['test' => 'jo']));

        $channel = $item;
        $this->platformHelper->executeAction($channel, 'complete_sync');

        return $channel;
    }
}