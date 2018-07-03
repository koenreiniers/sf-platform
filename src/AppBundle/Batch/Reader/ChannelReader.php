<?php
namespace AppBundle\Batch\Reader;

use AppBundle\Entity\Channel;
use Doctrine\ORM\EntityManager;
use Raw\Component\Batch\Item\InitializableInterface;
use Raw\Component\Batch\Item\ItemReaderInterface;
use Raw\Component\Batch\Step\StepExecutionAwareInterface;
use Raw\Component\Batch\Step\StepExecutionAwareTrait;

class ChannelReader implements ItemReaderInterface, InitializableInterface, StepExecutionAwareInterface
{
    use StepExecutionAwareTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var array
     */
    private $items;

    /**
     * ChannelReader constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function initialize()
    {

        $jobParameters = $this->stepExecution->getJobParameters();

        if(isset($jobParameters['channel'])) {
            $this->items = [$this->entityManager->getRepository(Channel::class)->find($jobParameters['channel'])];
        } else {
            $this->items = $this->entityManager->getRepository(Channel::class)->findAll();
        }

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