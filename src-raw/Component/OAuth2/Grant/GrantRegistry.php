<?php
namespace Raw\Component\OAuth2\Grant;

use Raw\Component\OAuth2\Grant\Exception\GrantTypeNotFoundException;

class GrantRegistry
{
    /**
     * @var GrantTypeInterface[]
     */
    private $types;

    /**
     * GrantRegistry constructor.
     * @param GrantTypeInterface[] $types
     */
    public function __construct(array $types)
    {
        $this->types = [];
        foreach($types as $type) {
            $this->types[$type->getName()] = $type;
        }
    }

    /**
     * @param string $name
     * @return GrantTypeInterface
     * @throws GrantTypeNotFoundException
     */
    public function getType($name)
    {
        if(!$this->hasType($name)) {
            throw new GrantTypeNotFoundException($name);
        }
        return $this->types[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasType($name)
    {
        return isset($this->types[$name]);
    }
}