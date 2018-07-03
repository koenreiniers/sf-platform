<?php
namespace Raw\Component\OAuth2\Grant;

use Raw\Component\OAuth2\Grant;

class GrantProvider
{
    /**
     * @var GrantRegistry
     */
    private $registry;

    /**
     * GrantProvider constructor.
     * @param GrantRegistry $registry
     */
    public function __construct(GrantRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param string $type
     * @param array $arguments
     * @return Grant
     * @throws Grant\Exception\GrantException
     */
    public function getGrant($type, array $arguments)
    {
        $type = $this->registry->getType($type);
        $grant = new Grant($type, $arguments);
        $type->grant($grant);

        if($grant->getUser() === null) {
            throw new Grant\Exception\GrantException('Not granted');
        }

        return $grant;
    }
}