<?php
namespace Raw\Bundle\BatchBundle\Repository;

use Doctrine\ORM\EntityRepository;

class JobInstanceRepository extends EntityRepository
{
    public function findOverdueForCron()
    {
        $qb = $this->createQueryBuilder('jobInstance');

        $qb
            ->andWhere('jobInstance.cronEnabled = true')
            ->andWhere('jobInstance.cronNextRunAt <= :now')
            ->setParameter('now', new \DateTime())
            //->andWhere('jobInstance.cronExpression IS NOT NULL')
        ;

        return $qb->getQuery()->getResult();
    }
}