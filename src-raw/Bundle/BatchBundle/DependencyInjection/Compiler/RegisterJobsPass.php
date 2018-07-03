<?php
namespace Raw\Bundle\BatchBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterJobsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $loaderDefinition = $container->findDefinition('raw_batch.job_loader');

        $idMap = [];
        foreach($container->findTaggedServiceIds('raw_batch.job') as $serviceId => $tags) {
            foreach($tags as $attributes) {
                $alias = isset($attributes['alias']) ? $attributes['alias'] : $serviceId;
                $idMap[$alias] = $serviceId;
            }
        }

        $loaderDefinition->replaceArgument(1, $idMap);
    }
}