<?php
namespace Raw\Bundle\SassBundle\DependencyInjection;

use Raw\Component\Sass\SassApp;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Yaml\Yaml;

class RawSassExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.yml');


        $locator = new FileLocator(__DIR__.'/../Resources/config/sass');
        $filename = $locator->locate('default.yml');

        $container->addResource(new FileResource($filename));
        $data = Yaml::parse(file_get_contents($filename))['raw_sass']['apps']['default'];

        $imports = [];
        foreach($data['imports'] as $import) {
            $imports[] = $import;
        }
        $variables = $data['variables'];
        $appDefinition = new Definition(SassApp::class, [
            'default',
            $imports,
            $variables,
        ]);
        $registry = $container->findDefinition('raw_sass.sass');

        $args = [$appDefinition];

        $registry->replaceArgument(0, $args);
    }
}