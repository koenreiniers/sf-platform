<?php
namespace Raw\Component\Menu\Provider;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Loader\ArrayLoader;
use Knp\Menu\Provider\MenuProviderInterface;
use Raw\Component\Menu\MenuConfiguration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class YamlFileMenuProvider implements MenuProviderInterface
{
    /**
     * @var array
     */
    private $pathsByName = [];

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * YamlFileMenuProvider constructor.
     * @param array $pathsByName
     * @param FactoryInterface $factory
     */
    public function __construct(array $pathsByName, FactoryInterface $factory)
    {
        $this->pathsByName = $pathsByName;
        $this->factory = $factory;
    }

    public function get($name, array $options = array())
    {
        $paths = $this->pathsByName[$name];

        $configs = [];

        $loader = new ArrayLoader($this->factory);

        foreach($paths as $path) {
            $content = file_get_contents($path);
            $config = Yaml::parse($content);

            $configs[] = current($config);

        }

        $processor = new Processor();
        $builder = new MenuConfiguration();
        $data = $processor->processConfiguration($builder, $configs);

        $menu = $loader->load($data);
        return $menu;
    }

    public function has($name, array $options = array())
    {
        return isset($this->pathsByName[$name]) && count($this->pathsByName[$name]) > 0;
    }
}