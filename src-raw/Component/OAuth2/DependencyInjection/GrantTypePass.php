<?php
namespace Raw\Component\OAuth2\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GrantTypePass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $registryId;

    /**
     * @var string
     */
    private $tagName;

    /**
     * GrantTypePass constructor.
     * @param $registryId
     * @param $tagName
     */
    public function __construct($registryId = 'raw_api.oauth2.grant_registry', $tagName = 'oauth2.grant_type')
    {
        $this->registryId = $registryId;
        $this->tagName = $tagName;
    }


    public function process(ContainerBuilder $container)
    {
        $def = $container->findDefinition($this->registryId);

        $args = [];
        foreach($container->findTaggedServiceIds($this->tagName) as $serviceId => $tags) {
            $args[] = new Reference($serviceId);
        }

        $def->replaceArgument(0, $args);
    }
}