<?php
namespace Raw\Bundle\ApiBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class OAuth2Factory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.oauth2.'.$id;
        $container->setDefinition($providerId, new ChildDefinition('raw_api.security.authentication.provider.oauth2'));

        $listenerId = 'security.authentication.listener.oauth2.'.$id;
        $listener = $container->setDefinition($listenerId, new ChildDefinition('raw_api.security.authentication.listener.oauth2'));

        return [
            $providerId, $listenerId, $defaultEntryPoint
        ];
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'oauth2';
    }

    public function addConfiguration(NodeDefinition $builder)
    {
    }
}