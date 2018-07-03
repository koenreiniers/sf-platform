<?php
namespace Raw\Filter\Context\Loader;

use Raw\Filter\Context\ContextBuilder;
use Raw\Filter\Context\FilterDefinition;

class ArrayLoader implements LoaderInterface
{
    /**
     * @var ContextBuilder
     */
    private $contextBuilder;

    /**
     * ArrayLoader constructor.
     * @param ContextBuilder $contextBuilder
     */
    public function __construct(ContextBuilder $contextBuilder)
    {
        $this->contextBuilder = $contextBuilder;
    }


    public function load($resource, $type = null)
    {
        $defaults = [
            'filters' => [],
        ];
        $options = array_merge($defaults, $resource);
        foreach($options['filters'] as $name => $filterConfig) {
            $defaults = [
                'field' => null,
                'type' => null,
            ];
            $filterConfig = array_merge($defaults, $filterConfig);
            $filterConfig['field'] = $filterConfig['field'] ?: $name;
            $filterOptions = $filterConfig;
            #unset($filterOptions['type']);
            unset($filterOptions['field']);
            $this->contextBuilder->setDefinition($name, new FilterDefinition($filterConfig['field'], $filterConfig['type'], $filterOptions));
        }
    }
}