<?php
namespace Raw\Bundle\MenuBundle;

use Raw\Bundle\MenuBundle\DependencyInjection\Compiler\RegisterMenuPathsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RawMenuBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterMenuPathsPass());
    }
}