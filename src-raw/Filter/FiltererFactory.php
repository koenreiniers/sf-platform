<?php
namespace Raw\Filter;

use Doctrine\ORM\QueryBuilder;
use Raw\Component\Grid\DataSource\DataSource;
use Raw\Filter\Adapter\DataSourceAdapter;
use Raw\Filter\Adapter\DoctrineOrmAdapter;
use Raw\Filter\Context\ContextBuilder;
use Raw\Filter\Storage\InMemoryStorage;

class FiltererFactory
{
    /**
     * @var FilterRegistry
     */
    private $registry;

    /**
     * @var FilterStorageInterface
     */
    private $storage;

    /**
     * FiltererFactory constructor.
     * @param FilterRegistry $registry
     */
    public function __construct(FilterRegistry $registry, FilterStorageInterface $storage)
    {
        $this->registry = $registry;
        $this->storage = $storage;
    }

    public function createAdapter($source)
    {
        if($source instanceof QueryBuilder) {
            return new DoctrineOrmAdapter($source);
        } else if($source instanceof DataSource) {
            return new DataSourceAdapter($source);
        }
        throw new \Exception('Cannot create adapter for this data source');
    }

    public function createBuilder()
    {
        $builder = new ContextBuilder($this->registry);
        $builder->setStorage(new InMemoryStorage());
        return $builder;
    }
}