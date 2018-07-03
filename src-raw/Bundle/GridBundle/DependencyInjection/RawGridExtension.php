<?php
namespace Raw\Bundle\GridBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Raw\Filter\Type;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\VarDumper\VarDumper;

class RawGridExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.yml');


        $this->removeUnavailableExtensions($container);


//
//        $args = [
//
//                        'base' => new Type\BaseFilterType(),
//                        'string' => new Type\StringFilterType(),
//                        'integer' => new Type\IntegerFilterType(),
//                        'enum' => new Type\EnumFilterType(),
//                        'datetime' => new Type\DateTimeFilterType(),
//                        'boolean' => new Type\BooleanFilterType(),
//
//        ];
//
//        $argz = [];
//        foreach($args as $name => $arg) {
//            $class = get_class($arg);
//            $id = 'raw_filter.filter.'.$name;
//
//            $container->setDefinition($id, new Definition($class));
//            $argz[$name] = new Reference($id);
//
//        }
//
//        $container->findDefinition('raw_filter.registry')
//            ->replaceArgument(0, $argz);
    }

    private function removeUnavailableExtensions(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        $dependencies = [
            'raw_grid.grid.extension.mass_action' => ['RawBatchBundle'],
        ];

        foreach($dependencies as $serviceId => $requiredBundles) {
            foreach($requiredBundles as $bundleName) {
                if(!isset($bundles[$bundleName])) {
                    $container->removeDefinition($serviceId);
                    continue 2;
                }
            }
        }
    }
}