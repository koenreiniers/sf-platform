<?php
namespace Raw\Component\Statistics\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\VarDumper\VarDumper;

class RegisterStatisticLoadersPass implements CompilerPassInterface
{
    private $registryId = 'raw_dashboard.statistics.loader.chain';

    private $tagName = 'statistics.loader';

    /**
     * RegisterStatisticLoadersPass constructor.
     * @param string $tagName
     * @param string $registryId
     */
    public function __construct($tagName = 'statistics.loader', $registryId = 'raw_dashboard.statistics.loader.chain')
    {
        $this->tagName = $tagName;
        $this->registryId = $registryId;
    }


    public function process(ContainerBuilder $container)
    {
        $registry = $container->findDefinition($this->registryId);

        $args = [];
        foreach($container->findTaggedServiceIds($this->tagName) as $serviceId => $tags) {
            $args[] = new Reference($serviceId);
        }

        $registry->replaceArgument(0, $args);
    }
}