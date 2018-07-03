<?php
namespace Raw\Component\OAuth2\Grant;

use Raw\Component\OAuth2\Grant;
use Symfony\Component\Security\Core\User\UserInterface;

interface GrantTypeInterface
{
    /**
     * @param Grant $grant
     * @return UserInterface
     * @throws Exception\GrantException
     */
    public function grant(Grant $grant);

    /**
     * @return string
     */
    public function getName();
}