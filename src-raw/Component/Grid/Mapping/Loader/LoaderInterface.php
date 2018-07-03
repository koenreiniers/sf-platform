<?php
namespace Raw\Component\Grid\Mapping\Loader;

use Raw\Component\Grid\Grid;
use Raw\Component\Grid\Mapping\GridMetadataBuilder;

interface LoaderInterface
{
    /**
     * @param GridMetadataBuilder $builder
     * @return bool - True on success, false on failure
     */
    public function load(GridMetadataBuilder $builder);
}