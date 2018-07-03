<?php
namespace Raw\Component\Grid\Mapping;

use Raw\Component\Grid\GridRegistry;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class GridMetadataFactory
{
    /**
     * @var AdapterInterface
     */
    private $cache;

    /**
     * @var Loader\LoaderInterface
     */
    private $loader;

    /**
     * @var GridMetadata[]
     */
    private $loaded = [];

    /**
     * @var GridRegistry
     */
    private $registry;

    /**
     * GridMetadataFactory constructor.
     * @param Loader\LoaderInterface $loader
     * @param GridRegistry $registry
     * @param AdapterInterface $cache
     */
    public function __construct(Loader\LoaderInterface $loader, GridRegistry $registry, AdapterInterface $cache = null)
    {
        $this->loader = $loader;
        $this->registry = $registry;
        $this->cache = null;//$cache;
    }

    private function tryCacheFetch($name)
    {
        if($this->cache === null) {
            return null;
        }
        $cacheItem = $this->cache->getItem($name);
        if($cacheItem->isHit()) {
            return $cacheItem->get();
        }
        return null;
    }

    private function tryCacheSave(GridMetadata $metadata)
    {
        if($this->cache === null) {
            return;
        }
        $cacheItem = $this->cache->getItem($metadata->getName());
        $cacheItem->set($metadata);
        $this->cache->save($cacheItem);
    }

    private function loadMetadata(GridMetadataBuilder $builder)
    {
        if(!$this->loader->load($builder)) {
            throw new \Exception('No metadata found for grid "%s"', $builder->getName());
        }
        foreach($this->registry->getExtensions() as $extension) {
            if(!$builder->hasExtensionConfigs($extension->getAlias())) {
                continue;
            }
            $extension->load($builder->getExtensionConfigs($extension->getAlias()), $builder);
        }
    }

    /**
     * @param string $name
     * @return GridMetadata
     * @throws \Exception
     */
    private function createMetadata($name)
    {
        $builder = new GridMetadataBuilder($name);

        $this->loadMetadata($builder);

        return $builder->getGridMetadata();
    }

    public function getMetadataFor($name)
    {
        if(isset($this->loaded[$name])) {
            return $this->loaded[$name];
        }
        if(($metadata = $this->tryCacheFetch($name)) !== null) {
            return $this->loaded[$name] = $metadata;
        }

        $metadata = $this->createMetadata($name);

        $this->tryCacheSave($metadata);

        return $this->loaded[$name] = $metadata;
    }
}