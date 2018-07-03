<?php
namespace Raw\Filter\Context;

use Raw\Filter\Context\FilterDefinition;
use Raw\Filter\FilterAdapter;
use Raw\Filter\FilterContext;
use Raw\Filter\Filterer;
use Raw\Filter\FilterRegistry;
use Raw\Filter\FilterStorageInterface;

class ContextBuilder extends FilterContext
{

    private $locked = false;

    private function verifyNotLocked()
    {
        if($this->locked) {
            throw new \Exception('Context is already locked');
        }
    }

    /**
     * @return Filterer
     */
    public function getFilterer()
    {
        return new Filterer($this->getContext());
    }

    public function __construct(FilterRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function setStorage(FilterStorageInterface $storage)
    {
        $this->verifyNotLocked();
        $this->storage = $storage;
        return $this;
    }

    public function setAdapter(FilterAdapter $adapter)
    {
        $this->verifyNotLocked();
        $this->adapter = $adapter;
        return $this;
    }

    public function setDefinition($name, FilterDefinition $definition)
    {
        $this->verifyNotLocked();
        $this->definitions[$name] = $definition;
        return $this;
    }

    public function getContext()
    {
        $context = clone $this;
        $context->locked = true;

        $compiler = new DefinitionCompiler($this->registry);
        foreach($context->definitions as $name => $definition) {
            $context->definitions[$name] = $compiler->compile($definition);
        }

        return $context;
    }
}