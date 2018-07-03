<?php
namespace Raw\Bundle\SearchBundle\DependencyInjection\Compiler;

use Raw\Search\Populator\ChainPopulator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\VarDumper\VarDumper;

class RegisterPopulatorsPass implements CompilerPassInterface
{
    const TAG_NAME = 'search.populator';
    const ARG_INDEX = 1;

    public function process(ContainerBuilder $container)
    {

        $registryDef = $container->findDefinition('raw_search.registry');

        $taggedServiceIds = $container->findTaggedServiceIds(self::TAG_NAME);

        $populatorsArg = [];

        /** @var Definition[] $populatorDefinitions */
        $populatorDefinitions = [];

        foreach($taggedServiceIds as $serviceId => $tags) {
            foreach($tags as $attributes) {
                $indexId = $attributes['index'];
                if(!isset($populatorDefinitions[$indexId])) {
                    $populatorDefinitions[$indexId] = new Definition(ChainPopulator::class, [[]]);
                    $svcId = 'raw_search.populator.index.'.$indexId;
                    $container->setDefinition($svcId, $populatorDefinitions[$indexId]);
                    $populatorsArg[$indexId] = new Reference($svcId);
                }
                $chained = $populatorDefinitions[$indexId]->getArgument(0);
                $chained[] = new Reference($serviceId);
                $populatorDefinitions[$indexId]->replaceArgument(0, $chained);
            }
        }
        $registryDef->replaceArgument(self::ARG_INDEX, $populatorsArg);

    }
}