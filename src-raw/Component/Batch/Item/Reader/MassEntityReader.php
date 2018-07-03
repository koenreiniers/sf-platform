<?php
namespace Raw\Component\Batch\Item\Reader;

use Doctrine\ORM\EntityManager;
use Raw\Component\Batch\Item\InitializableInterface;
use Raw\Component\Batch\Item\ItemReaderInterface;
use Raw\Component\Batch\Step\StepExecutionAwareInterface;
use Raw\Component\Batch\Step\StepExecutionAwareTrait;
use Raw\Component\Batch\StepExecution;

class MassEntityReader implements ItemReaderInterface, StepExecutionAwareInterface, InitializableInterface
{
    use StepExecutionAwareTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var array
     */
    private $items = [];

    /**
     * MassEntityReader constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }



    public function initialize()
    {

        $params = $this->stepExecution->getJobParameters();
        $from = $params['from'];
        $ids = $params['ids'];
        $qb = $this->entityManager->createQueryBuilder()
            ->from($from, 'o')
            ->select('o')
            ->andWhere('o.id IN (:ids)')
            ->setParameter('ids', $ids)
        ;

        $this->items = $qb->getQuery()->getResult();
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