<?php
namespace Raw\Search\Repository;

use Raw\Search\Hydrator\HydratorInterface;
use Raw\Search\Repository;
use Raw\Search\SearchIndex;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SearchRepositoryFactory
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * SearchRepositoryFactory constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $expr
     * @return HydratorInterface
     * @throws \Exception
     */
    private function getHydrator($expr)
    {
        if(substr($expr, 0, 1) === '@') {
            $serviceId = substr($expr, 1);
            return $this->container->get($serviceId);
        }
        throw new \Exception('TODO');
    }

    public function create(SearchIndex $index)
    {
        $repository = new Repository($index);

        if(($defaultHydrator = $index->getConfig()->getDefaultHydrator()) !== null) {
            $repository->setDefaultHydrator($this->getHydrator($defaultHydrator));
        }

        return $repository;
    }
}