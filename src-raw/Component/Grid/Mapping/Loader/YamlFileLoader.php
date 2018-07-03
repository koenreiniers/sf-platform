<?php
namespace Raw\Component\Grid\Mapping\Loader;

use Raw\Component\Grid\Grid;
use Raw\Component\Grid\Mapping\GridMetadataBuilder;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Yaml\Yaml;

class YamlFileLoader implements LoaderInterface
{
    /**
     * @var array
     */
    private $pathsByName = [];

    /**
     * YamlFileLoader constructor.
     * @param array $pathsByName
     */
    public function __construct(array $pathsByName)
    {
        $this->pathsByName = $pathsByName;
    }

    public function load(GridMetadataBuilder $gridMetadata)
    {
        if(!isset($this->pathsByName[$gridMetadata->getName()]) || count($this->pathsByName[$gridMetadata->getName()]) === 0) {
            return false;
        }
        $name = $gridMetadata->getName();

        $paths = $this->pathsByName[$name];

        foreach($paths as $path) {
            $content = file_get_contents($path);
            $config = Yaml::parse($content);
            $config = current($config);

            foreach($config as $extensionName => $extensionConfig) {
                $gridMetadata->addExtensionConfig($extensionName, $extensionConfig);
            }
        }

        return true;
    }


}