<?php
namespace Raw\Bundle\PagerBundle;

use Raw\Pager\Extension\DependencyInjection\Compiler\RegisterExtensionsPass;
use Raw\Pager\Extension\DependencyInjection\Compiler\TwigLoaderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RawPagerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterExtensionsPass());
        $container->addCompilerPass(new TwigLoaderPass());
    }
}