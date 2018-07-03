<?php
namespace Raw\Component\Batch\Item\Processor;

use Doctrine\ORM\EntityManager;
use Raw\Component\Batch\Item\ItemProcessorInterface;

class DoctrineRemoveProcessor implements ItemProcessorInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * DoctrineRemoveProcessor constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function process($item)
    {
        $this->entityManager->remove($item);
        return $item;
    }
}