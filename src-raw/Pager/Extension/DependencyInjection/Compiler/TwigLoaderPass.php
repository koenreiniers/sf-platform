<?php
namespace Raw\Pager\Extension\DependencyInjection\Compiler;

use Raw\Pager\Pager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\VarDumper\VarDumper;

class TwigLoaderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $reflClass = new \ReflectionClass(Pager::class);



        $viewPath = dirname($reflClass->getFileName()).'/Resources/views/Pager';


        $loaderDefinition = $container->getDefinition('twig.loader.filesystem');
            $loaderDefinition->addMethodCall('addPath', [$viewPath]);

    }
}