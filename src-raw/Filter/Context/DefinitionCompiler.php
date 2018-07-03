<?php
namespace Raw\Filter\Context;

use Raw\Filter\Context\FilterDefinition;
use Raw\Filter\FilterAdapter;
use Raw\Filter\FilterContext;
use Raw\Filter\FilterRegistry;
use Symfony\Component\VarDumper\VarDumper;

class DefinitionCompiler
{
    /**
     * @var FilterRegistry
     */
    private $registry;

    /**
     * DefinitionCompiler constructor.
     * @param FilterRegistry $registry
     */
    public function __construct(FilterRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param \Raw\Filter\Context\FilterDefinition $definition
     * @return \Raw\Filter\Context\FilterDefinition
     */
    public function compile(FilterDefinition $definition)
    {
        $options = $definition->getOptions();
        $options = $this->registry->getType($definition->getType())->getOptionsResolver()->resolve($options);
        $compiled = new FilterDefinition($definition->getFieldName(), $definition->getType(), $options);
        return $compiled;
    }
}