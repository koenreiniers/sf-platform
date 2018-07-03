<?php
namespace Raw\Component\Grid\Mapping;

use Symfony\Component\VarDumper\VarDumper;

class GridMetadataBuilder extends GridMetadata
{
    /**
     * @var bool
     */
    private $locked = false;

    /**
     * @var array
     */
    private $extensionConfigsByName = [];

    private function verifyNotLocked()
    {
        if($this->locked) {
            throw new \Exception('Metadata cannot be changed after it has been locked');
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setProperty($name, $value)
    {
        $this->verifyNotLocked();
        $this->properties[$name] = $value;
        return $this;
    }

    /**
     * @return GridMetadata
     */
    public function getGridMetadata()
    {
        $metadata = clone $this;
        $metadata->locked = true;

        $configs = [];

        foreach($metadata->extensionConfigsByName as $extensionName => $extensionConfigs) {
            $extensionConfig = [];
            foreach($extensionConfigs as $partialConfig) {
                $extensionConfig = array_merge($extensionConfig, $partialConfig);
            }
            $configs[$extensionName] = $extensionConfig;
        }
        $metadata->extensionConfigs = $configs;
        $this->extensionConfigsByName = [];

        return $metadata;
    }

    /**
     * @param string $name
     * @param array $mapping
     * @return $this
     * @throws \Exception
     */
    public function mapColumn($name, array $mapping)
    {
        $this->verifyNotLocked();

        $config = [
            $name => $mapping,
        ];
        return $this->addExtensionConfig('columns', $config);
    }

    public function hasExtensionConfigs($name)
    {
        if(!isset($this->extensionConfigsByName[$name])) {
            $this->extensionConfigsByName[$name] = [];
        }
        return isset($this->extensionConfigsByName[$name]);
    }

    public function getExtensionConfigs($name)
    {
        return $this->extensionConfigsByName[$name];
    }

    /**
     * @param string $name
     * @param array $config
     * @return $this
     * @throws \Exception
     */
    public function addExtensionConfig($name, array $config)
    {
        $this->verifyNotLocked();

        if(!isset($this->extensionConfigsByName[$name])) {
            $this->extensionConfigsByName[$name] = [];
        }
        $this->extensionConfigsByName[$name][] = $config;
        return $this;
    }


}