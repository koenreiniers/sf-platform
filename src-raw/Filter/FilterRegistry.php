<?php
namespace Raw\Filter;

use Raw\Filter\Exception\FilterValidationException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterRegistry
{
    /**
     * @var ResolvedFilterType[]
     */
    private $resolvedTypes = [];

    /**
     * @var FilterType[]
     */
    private $types;

    /**
     * @var array
     */
    private $aliases = [];

    /**
     * FilterRegistry constructor.
     * @param FilterType[] $types
     */
    public function __construct(array $types)
    {
        $this->types = [];
        foreach($types as $alias => $type) {
            $this->aliases[$alias] = get_class($type);
            $this->types[get_class($type)] = $type;
        }
    }

    private function resolveName($name)
    {
        if(isset($this->aliases[$name])) {
            $name = $this->aliases[$name];
        }
        return $name;
    }

    public function getType($name)
    {
        $name = $this->resolveName($name);

        if(isset($this->resolvedTypes[$name])) {
            return $this->resolvedTypes[$name];
        }
        $type = $this->types[$name];
        return $this->resolvedTypes[$name] = $this->resolve($type);
    }

    private function resolve(FilterType $inner)
    {
        $parent = null;
        if($inner->getParent() !== null) {
            $parent = $this->getType($inner->getParent());
        }
        return new ResolvedFilterType($inner, $parent);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasType($name)
    {
        $name = $this->resolveName($name);
        return isset($this->types[$name]);
    }
}