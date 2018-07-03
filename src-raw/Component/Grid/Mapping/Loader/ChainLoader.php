<?php
namespace Raw\Component\Grid\Mapping\Loader;

use Raw\Component\Grid\Grid;
use Raw\Component\Grid\Mapping\GridMetadataBuilder;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Yaml\Yaml;

class ChainLoader implements LoaderInterface
{
    /**
     * @var LoaderInterface[]
     */
    private $loaders = [];

    /**
     * ChainLoader constructor.
     * @param LoaderInterface[] $loaders
     */
    public function __construct(array $loaders)
    {
        $this->loaders = $loaders;
    }


    public function load(GridMetadataBuilder $gridMetadata)
    {
        $success = false;
        foreach($this->loaders as $loader) {
            $success = $success || $loader->load($gridMetadata);
        }
        return $success;
    }


}