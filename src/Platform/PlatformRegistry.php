<?php
namespace Platform;

class PlatformRegistry
{
    /**
     * @var PlatformAdapterInterface[]
     */
    private $adapters;

    /**
     * PlatformRegistry constructor.
     * @param \array[] $adapters
     */
    public function __construct(array $adapters)
    {
        $this->adapters = $adapters;
    }

    /**
     * @param string $name
     * @return PlatformAdapterInterface
     */
    public function getPlatformAdapter($name)
    {
        return $this->adapters[$name];
    }

    /**
     * @return string[]
     */
    public function getAdapterNames()
    {
        return array_keys($this->adapters);
    }
}