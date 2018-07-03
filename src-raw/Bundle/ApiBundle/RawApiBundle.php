<?php
namespace Raw\Bundle\ApiBundle;

use Raw\Bundle\ApiBundle\DependencyInjection\Security\Factory\OAuth2Factory;
use Raw\Component\OAuth2\DependencyInjection\GrantTypePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RawApiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new OAuth2Factory());

        $container->addCompilerPass(new GrantTypePass());
    }
}