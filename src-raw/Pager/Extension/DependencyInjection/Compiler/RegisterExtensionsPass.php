<?php
namespace Raw\Pager\Extension\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterExtensionsPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $factoryId;

    /**
     * @var string
     */
    private $tagName;

    /**
     * RegisterExtensionsPass constructor.
     * @param string $tagName
     */
    public function __construct($factoryId = 'raw_pager.factory', $tagName = 'raw_pager.extension')
    {
        $this->factoryId = $factoryId;
        $this->tagName = $tagName;
    }

    public function process(ContainerBuilder $container)
    {
        $factoryDefinition = $container->findDefinition($this->factoryId);

        $extensions = [];
        foreach($container->findTaggedServiceIds($this->tagName) as $serviceId => $tags) {
            $extensions[] = new Reference($serviceId);
        }

        $factoryDefinition->replaceArgument(0, $extensions);

    }
}