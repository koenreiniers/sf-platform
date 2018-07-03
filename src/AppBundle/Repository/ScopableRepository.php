<?php
namespace AppBundle\Repository;

use AppBundle\Entity\UserScope;
use Doctrine\ORM\EntityRepository;

class ScopableRepository extends EntityRepository
{
    public function findScopedBy(array $criteria = [], $orderBy = null, $limit = null, $offset = null)
    {
        $userScope = $this->getEntityManager()->getRepository(UserScope::class)->findOneBy([]);

        if($userScope !== null && $userScope->getChannel() !== null) {
            $criteria['channel'] = $userScope->getChannel();
        }

        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }
}