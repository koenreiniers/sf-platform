<?php
namespace Raw\Component\Statistics\Loader;

use Raw\Component\Statistics\StatisticCollection;
use Raw\Component\Statistics\StatisticLoaderInterface;

class ChainLoader implements StatisticLoaderInterface
{
    /**
     * @var StatisticLoaderInterface[]
     */
    private $loaders;

    /**
     * ChainLoader constructor.
     * @param \Raw\Component\Statistics\StatisticLoaderInterface[] $loaders
     */
    public function __construct(array $loaders)
    {
        $this->loaders = $loaders;
    }

    public function load(StatisticCollection $statistics)
    {
        foreach($this->loaders as $loader) {
            $loader->load($statistics);
        }
    }
}