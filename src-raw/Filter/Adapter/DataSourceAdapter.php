<?php
namespace Raw\Filter\Adapter;

use Doctrine\ORM\Query;

use Raw\Component\Grid\DataSource\Adapter\DoctrineOrmAdapter;
use Raw\Component\Grid\DataSource\DataSource;
use Raw\Filter\FilterAdapter;
use Raw\Filter\Expr;


class DataSourceAdapter extends FilterAdapter
{
    /**
     * @var DataSource
     */
    private $dataSource;

    /**
     * DoctrineOrmAdapter constructor.
     * @param Query $query
     */
    public function __construct(DataSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    public function dispatch(Expr\Base $expression)
    {
        $adapter = $this->dataSource->getAdapter();
        if($adapter instanceof DoctrineOrmAdapter) {
            $wrapped = new \Raw\Filter\Adapter\DoctrineOrmAdapter($adapter->getQueryBuilder());
            $wrapped->dispatch($expression);
            return;
        }

        throw new \Exception('No adapter found');
    }
}