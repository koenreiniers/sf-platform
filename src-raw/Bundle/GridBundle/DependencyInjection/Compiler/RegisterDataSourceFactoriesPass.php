<?php
namespace Raw\Bundle\GridBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class RegisterDataSourceFactoriesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {

        $def = $container->findDefinition('raw_grid.data_source.factory.delegating');

        $extensions = [];

        foreach($container->findTaggedServiceIds('raw_grid.data_source_factory') as $serviceId => $tags) {
            $extensions[] = new Reference($serviceId);
        }

        $def->replaceArgument(0, $extensions);
    }
}