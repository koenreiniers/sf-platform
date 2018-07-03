<?php
namespace Raw\Bundle\VersioningBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Raw\Bundle\VersioningBundle\Behaviour\VersionableInterface;
use Raw\Bundle\VersioningBundle\Entity\Version;

class VersionRepository extends EntityRepository
{
    /**
     * @param VersionableInterface $resource
     * @return Version|null
     */
    public function findPreviousVersion(VersionableInterface $resource)
    {
        $qb = $this->createQueryBuilder('version')
            ->andWhere('version.resourceName = :resourceName')
            ->andWhere('version.resourceId = :resourceId')
            ->orderBy('version.number', 'DESC')
            ->setParameters([
                'resourceId' => $resource->getId(),
                'resourceName' => get_class($resource),
            ])
            ->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param VersionableInterface $resource
     * @return Version[]
     */
    public function findByResource(VersionableInterface $resource)
    {
        $qb = $this->createQueryBuilder('version')
            ->andWhere('version.resourceName = :resourceName')
            ->andWhere('version.resourceId = :resourceId')
            ->setParameters([
                'resourceId' => $resource->getId(),
                'resourceName' => get_class($resource),
            ]);
        return $qb->getQuery()->getResult();
    }
}