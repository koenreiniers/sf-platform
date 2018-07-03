<?php
namespace Raw\Bundle\GridBundle;

use Raw\Bundle\GridBundle\DependencyInjection\Compiler\RegisterDataSourceFactoriesPass;
use Raw\Bundle\GridBundle\DependencyInjection\Compiler\RegisterExtensionsPass;
use Raw\Bundle\GridBundle\DependencyInjection\Compiler\RegisterGridPathsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RawGridBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterDataSourceFactoriesPass());
        $container->addCompilerPass(new RegisterExtensionsPass());
        $container->addCompilerPass(new RegisterGridPathsPass());
    }
}