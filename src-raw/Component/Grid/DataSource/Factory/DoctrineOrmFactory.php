<?php
namespace Raw\Component\Grid\DataSource\Factory;

use Doctrine\ORM\EntityManager;
use Raw\Component\Grid\DataSource\Adapter\DoctrineOrmAdapter;
use Raw\Component\Grid\DataSource\DataSourceFactoryInterface;
use Raw\Component\Grid\DataSource\Doctrine\QueryBuilderLoader;

class DoctrineOrmFactory implements DataSourceFactoryInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * DoctrineOrmFactory constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create($type, array $options)
    {
        $query = $options['query'];
        $qb = $this->entityManager->createQueryBuilder();
        $loader = new QueryBuilderLoader($qb);
        $loader->load($query);
        return new DoctrineOrmAdapter($qb);
    }

    public function supports($type, array $options)
    {
        return $type === 'orm' && isset($options['query']);
    }
}