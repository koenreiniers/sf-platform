<?php
namespace Raw\Search\Hydrator;

use Doctrine\ORM\EntityManager;

class DoctrineEntityHydrator implements HydratorInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * DoctrineEntityHydrator constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function hydrateAll(array $hits)
    {
        $em = $this->entityManager;
        $idsByResourceName = [];
        foreach($hits as $hit) {
            if(!isset($idsByResourceName[$hit->resourceName])) {
                $idsByResourceName[$hit->resourceName] = [];
            }
            $idsByResourceName[$hit->resourceName][] = $hit->resourceId;
        }
        $results = [];
        foreach($idsByResourceName as $resourceName => $ids) {
            $qb = $em->createQueryBuilder()->from($resourceName, 'o')->select('o')->andWhere('o.id IN (:ids)')
                ->setParameter('ids', $ids);
            $results = array_merge($results, $qb->getQuery()->getResult());
        }
        return $results;
    }
}