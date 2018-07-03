<?php
namespace Raw\Component\Grid\DataSource\Adapter;

use Doctrine\ORM\QueryBuilder;
use Raw\Component\Grid\DataSource\Data;
use Raw\Pager\Adapter\DoctrineOrmAdapter as PagerAdapter;

class DoctrineOrmAdapter implements AdapterInterface
{
    /**
     * @var QueryBuilder
     */
    private $qb;

    /**
     * DoctrineOrmAdapter constructor.
     * @param QueryBuilder $qb
     */
    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->qb;
    }

    public function setParameter($name, $value)
    {
        $this->qb->setParameter($name, $value);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return Data
     * @throws \Exception
     */
    public function getData($offset, $limit)
    {
        $pager = new PagerAdapter($this->qb);
        $results = $this->qb->setMaxResults($limit)->setFirstResult($offset)->getQuery()->getArrayResult();
        $totalSize = $pager->getTotalCount();
        return new Data($results, $totalSize);
    }

    public function orderBy($field, $direction = 'ASC')
    {
        $this->qb->orderBy($field, $direction);
    }
}