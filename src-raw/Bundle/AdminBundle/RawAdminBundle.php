<?php
namespace Raw\Bundle\AdminBundle;

use Raw\Bundle\AdminBundle\DependencyInjection\Compiler\RegisterAdminsPass;
use Raw\Component\Crud\DependencyInjection\MappingPathsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RawAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterAdminsPass());
    }
}