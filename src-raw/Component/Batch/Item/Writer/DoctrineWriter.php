<?php
namespace Raw\Component\Batch\Item\Writer;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;
use Raw\Component\Batch\Item\ItemWriterInterface;
use Raw\Component\Batch\Step\StepExecutionAwareInterface;
use Raw\Component\Batch\Step\StepExecutionAwareTrait;

class DoctrineWriter implements ItemWriterInterface, StepExecutionAwareInterface
{
    use StepExecutionAwareTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * DoctrineWriter constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function write($items)
    {
        $this->stepExecution->setSummaryInfo('created', 0);
        $this->stepExecution->setSummaryInfo('updated', 0);
        foreach($items as $item) {
            $state = $this->entityManager->getUnitOfWork()->getEntityState($item);
            if($state !== UnitOfWork::STATE_NEW) {
                if(true || $this->entityManager->getUnitOfWork()->isEntityScheduled($item)) {
                    $this->stepExecution->incrementSummaryInfo('updated');
                }
                continue;
            }
            $this->entityManager->persist($item);
            $this->stepExecution->incrementSummaryInfo('created');
        }

        $this->entityManager->flush();
    }
}