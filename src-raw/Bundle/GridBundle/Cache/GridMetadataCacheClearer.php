<?php
namespace Raw\Bundle\GridBundle\Cache;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

class GridMetadataCacheClearer implements CacheClearerInterface
{
    /**
     * @var AdapterInterface
     */
    private $cache;

    /**
     * GridMetadataCacheClearer constructor.
     * @param AdapterInterface $cache
     */
    public function __construct(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    public function clear($cacheDir)
    {
        $this->cache->clear();
    }
}