<?php
namespace Raw\Bundle\VueBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\VarDumper\VarDumper;

class RawVueExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');


        $def = $container->findDefinition('raw_vue.registry');



        $apps = [];

        foreach($config['apps'] as $appName => $appConfig) {

            $app = [
                'templates' => [],
            ];

            foreach($appConfig['templates'] as $templateName => $templatePath) {
                $app['templates'][$templateName] = $templatePath;
            }

            $apps[$appName] = $app;
        }


        $def->replaceArgument(0, $apps);
    }
}