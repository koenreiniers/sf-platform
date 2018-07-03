<?php
namespace Raw\Bundle\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterAdminsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $taggedServiceIds = $container->findTaggedServiceIds('raw.admin');

        $admins = [];
        $aliases = [];

        foreach($taggedServiceIds as $serviceId => $tags) {

            $adminDefinition = $container->findDefinition($serviceId);
            $adminClass = $adminDefinition->getClass();

            foreach($tags as $attributes) {
                $alias = isset($attributes['alias']) ? $attributes['alias'] : $serviceId;
                $aliases[$alias] = $adminClass;
            }
            $admins[$adminClass] = new Reference($serviceId);
        }

        $registryDefinition = $container->findDefinition('raw_admin.registry');

        $registryDefinition->replaceArgument(0, $admins);
        $registryDefinition->replaceArgument(1, $aliases);
    }
}