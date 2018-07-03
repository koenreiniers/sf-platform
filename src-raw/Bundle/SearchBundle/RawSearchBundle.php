<?php
namespace Raw\Bundle\SearchBundle;

use Raw\Bundle\SearchBundle\DependencyInjection\Compiler\RegisterMappingsPass;
use Raw\Bundle\SearchBundle\DependencyInjection\Compiler\RegisterPopulatorsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RawSearchBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterMappingsPass());
        $container->addCompilerPass(new RegisterPopulatorsPass());
    }
}