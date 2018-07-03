<?php
namespace Raw\Component\Grid\DataSource\Adapter;

use Doctrine\ORM\QueryBuilder;
use Raw\Component\Grid\DataSource\Data;
use Raw\Pager\Adapter\DoctrineOrmAdapter as PagerAdapter;

interface AdapterInterface
{
    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setParameter($name, $value);

    /**
     * @param string $field
     * @param string $direction
     * @return void
     */
    public function orderBy($field, $direction = 'ASC');

    /**
     * @param int $offset
     * @param int $limit
     * @return Data
     */
    public function getData($offset, $limit);
}