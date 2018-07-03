<?php
namespace Raw\Search\Resource\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Andx;
use Raw\Search\Mapping\ClassMetadata;
use Raw\Search\Resource\ResourceTypeInterface;

class OrmResourceType implements ResourceTypeInterface
{
    const NAME = 'orm';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * OrmResourceType constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function convertExpr($expr)
    {
        if(is_string($expr)) {
            return $expr;
        }
        if(is_array($expr)) {
            if(count($expr) === 0) {
                return null;
            }

            $strList = [];
            $str = '(';
            foreach($expr as $e) {
                $strList[] = '('.$e.')';
            }
            $str .= implode(' AND ', $strList);
            $str.= ')';
            return $str;
        }
    }

    /**
     * @param ClassMetadata $classMetadata
     * @param mixed $expr
     * @return object[]
     */
    public function findBy(ClassMetadata $classMetadata, $expr)
    {
        $expr = $this->convertExpr($expr);
        $qb = $this->entityManager->getRepository($classMetadata->getClassName())->createQueryBuilder('o');
        if($expr !== null) {
            $qb->andWhere($expr);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param ClassMetadata $classMetadata
     * @return object[]
     */
    public function findAllResources(ClassMetadata $classMetadata)
    {
        return $this->entityManager->getRepository($classMetadata->getClassName())->findAll();
    }
}