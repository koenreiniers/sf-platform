<?php
namespace Raw\Component\Grid\DataSource\Factory;

use Raw\Component\Grid\DataSource\DataSourceFactoryInterface;

class DelegatingFactory implements DataSourceFactoryInterface
{
    /**
     * @var DataSourceFactoryInterface[]
     */
    private $factories = [];

    /**
     * DelegatingFactory constructor.
     * @param \Raw\Component\Grid\DataSource\DataSourceFactoryInterface[] $factories
     */
    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }

    private function findSupportedFactory($type, array $options)
    {
        foreach($this->factories as $factory) {
            if($factory->supports($type, $options)) {
                return $factory;
            }
        }
        return null;
    }

    public function create($type, array $options)
    {
        return $this->findSupportedFactory($type, $options)->create($type, $options);
    }

    public function supports($type, array $options)
    {
        return $this->findSupportedFactory($type, $options) !== null;
    }
}