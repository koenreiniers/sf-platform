<?php
namespace Raw\Component\Grid\DataSource\Doctrine;

use Doctrine\ORM\QueryBuilder;

class QueryBuilderLoader
{
    /**
     * @var QueryBuilder
     */
    private $qb;

    /**
     * QueryBuilderLoader constructor.
     * @param QueryBuilder $qb
     */
    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }

    public function load(array $query)
    {
        $qb = $this->qb;
        $query = array_merge([
            'from' => [],
            'select' => [],
            'parameters' => [],
            'where' => [],
            'join' => [],
        ], $query);

        foreach($query['from'] as $alias => $from) {
            $qb->from($from, $alias);
        }
        foreach($query['select'] as $select) {
            $qb->addSelect($select);
        }
        foreach($query['parameters'] as $key => $value) {
            $qb->setParameter($key, $value);
        }
        foreach($query['where'] as $where) {
            $qb->andWhere($where);
        }
        foreach($query['join'] as $joinData) {
            $defaults = [
                'join' => null,
                'alias' => null,
                'conditionType' => null,
                'indexBy' => null,
            ];
            $joinData = array_merge($defaults, $joinData);
            $qb->join($joinData['join'], $joinData['alias'], $joinData['conditionType'], $joinData['indexBy']);
        }
    }
}