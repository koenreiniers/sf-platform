<?php
namespace Raw\Component\Menu\Loader;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Loader\ArrayLoader;
use Knp\Menu\Loader\LoaderInterface;
use Symfony\Component\Yaml\Yaml;

class YamlFileLoader extends FileLoader
{
    protected $validExtensions = ['yml', 'yaml'];

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * YamlFileLoader constructor.
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function load($data)
    {
        $content = file_get_contents($data);
        $data = Yaml::parse($content);
        $data = current($data);
        $arrayLoader = new ArrayLoader($this->factory);
        return $arrayLoader->load($data);
    }
}