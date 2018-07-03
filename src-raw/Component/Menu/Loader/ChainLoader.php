<?php
namespace Raw\Component\Menu\Loader;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Loader\ArrayLoader;
use Knp\Menu\Loader\LoaderInterface;
use Symfony\Component\Yaml\Yaml;

class ChainLoader extends FileLoader
{
    /**
     * @var LoaderInterface[]
     */
    private $loaders;

    /**
     * ChainLoader constructor.
     * @param \Knp\Menu\Loader\LoaderInterface[] $loaders
     */
    public function __construct(array $loaders)
    {
        $this->loaders = $loaders;
    }

    public function supports($data)
    {
        return $this->findLoader($data) !== null;
    }

    private function findLoader($data)
    {
        foreach($this->loaders as $loader) {
            if($loader->supports($data)) {
                return $loader;
            }
        }
        return null;
    }

    public function load($data)
    {
        return $this->findLoader($data)->load($data);
    }
}