<?php
namespace Raw\Component\Widget\DependencyInjection;

use Raw\Bundle\DashboardBundle\Entity\Widget;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\VarDumper\VarDumper;

class RegisterWidgetsPass implements CompilerPassInterface
{
    private $registryId = 'raw_dashboard.widget_registry';

    private $tagName = 'widget.type';

    public function process(ContainerBuilder $container)
    {
        $registry = $container->findDefinition($this->registryId);

        $args = [];

        foreach($container->findTaggedServiceIds($this->tagName) as $serviceId => $tags) {


            foreach($tags as $attributes) {
                $alias = isset($attributes['alias']) ? $attributes['alias'] : $serviceId;
                $args[$alias] = new Reference($serviceId);
            }
        }

        $registry->replaceArgument(0, $args);
    }
}