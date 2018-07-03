<?php
namespace Raw\Pager\Adapter;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\VarDumper\VarDumper;

class DoctrineOrmAdapter implements AdapterInterface
{
    /**
     * @var Query
     */
    private $query;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var bool
     */
    private $fetchJoinCollections = true;

    /**
     * DoctrineOrmAdapter constructor.
     * @param Query|QueryBuilder $query
     */
    public function __construct($query, $fetchJoinCollections = true)
    {
        $this->query = $query;
        $this->fetchJoinCollections = $fetchJoinCollections;
    }

    private function initPaginator()
    {
        if($this->paginator !== null) {
            return;
        }
        $query = $this->query;
        if($query instanceof QueryBuilder) {
            $query = $query->getQuery();
        }

        $this->paginator = new Paginator($query, $this->fetchJoinCollections);
    }

    public function getSlice($offset, $limit)
    {
        //$this->initPaginator();
        $query = $this->query;
        if($query instanceof QueryBuilder) {
            $query->setFirstResult($offset)->setMaxResults($limit);
            $query = $query->getQuery();
        }
        return $query->getResult();
        return $this->paginator->getQuery()->setFirstResult($offset)->setMaxResults($limit)->getResult();
    }

    public function getTotalCount()
    {
        //$this->initPaginator();

        /** @var QueryBuilder $countQb */
        $countQb = clone $this->query;



        if(!$countQb instanceof QueryBuilder) {
            throw new \Exception('todo');
        }

        $aliases = $countQb->getRootAliases();
        $alias = $aliases[0];

        $countQb->select('COUNT('.$alias.')')->setMaxResults(null)->setFirstResult(null);

        return (int)$countQb->getQuery()->getSingleScalarResult();

        return $this->paginator->count();
    }
}