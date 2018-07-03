<?php
namespace Raw\Search\Index;

use Raw\Search\Registry;
use Raw\Search\SearchIndexConfig;
use Raw\Search\SearchIndexConfigBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndexConfigProvider
{
    /**
     * @var array
     */
    private $configs = [];

    /**
     * @var SearchIndexConfig[]
     */
    private $builtConfigs = [];

    /**
     * @var Registry
     */
    private $registry;

    /**
     * IndexConfigProvider constructor.
     * @param Registry $registry
     * @param array $configs
     */
    public function __construct(Registry $registry, array $configs)
    {
        $this->registry = $registry;
        $this->configs = $configs;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasConfig($name)
    {
        return isset($this->configs[$name]);
    }

    private function buildConfig($name)
    {
        $config = $this->configs[$name];
        $driver = $this->registry->getDriver($config['driver']);
        $resolver = new OptionsResolver();
        $driver->configureOptions($resolver);
        $driverOptions = $resolver->resolve($config['driver_options']);
        $builder = SearchIndexConfigBuilder::create($config)
            ->setDriver($driver)
            ->setDriverOptions($driverOptions)
            ->setRegistry($this->registry)
        ;
        return $builder->getConfig();
    }

    /**
     * @param string $name
     * @return SearchIndexConfig
     * @throws \Exception
     */
    public function getConfig($name)
    {
        if(isset($this->builtConfigs[$name])) {
            return $this->builtConfigs[$name];
        }
        if(!$this->hasConfig($name)) {
            throw new \Exception(sprintf('No config found for index "%s"', $name));
        }
        return $this->builtConfigs[$name] = $this->buildConfig($name);
    }
}