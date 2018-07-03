<?php
namespace Raw\Component\Grid\DataSource;

use Raw\Component\Grid\DataSource\Adapter\AdapterInterface;

interface DataSourceFactoryInterface
{
    /**
     * @param string $type
     * @param array $options
     * @return AdapterInterface
     */
    public function create($type, array $options);

    /**
     * @param string $type
     * @param array $options
     * @return bool
     */
    public function supports($type, array $options);
}