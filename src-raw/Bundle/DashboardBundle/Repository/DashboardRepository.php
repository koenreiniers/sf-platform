<?php
namespace Raw\Bundle\DashboardBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Raw\Bundle\DashboardBundle\Entity\Dashboard;
use Raw\Bundle\UserBundle\Entity\User;

class DashboardRepository extends EntityRepository
{
    /**
     * @param User $owner
     * @return Dashboard
     */
    public function findDefaultDashboardByOwner(User $owner)
    {
        $qb = $this->createQueryBuilder('dashboard')
            ->select('dashboard')
            ->where('dashboard.owner = :owner')
            ->andWhere('dashboard.default = true')
            ->setMaxResults(1)
            ->setParameters([
                'owner' => $owner,
            ]);
        return $qb->getQuery()->getOneOrNullResult();
    }
}