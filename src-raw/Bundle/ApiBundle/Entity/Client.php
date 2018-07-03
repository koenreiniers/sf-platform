<?php
namespace Raw\Bundle\ApiBundle\Entity;

use Raw\Bundle\UserBundle\Behaviour\OwnableInterface;
use Raw\Bundle\UserBundle\Entity\User;
use Raw\Component\OAuth2\Model\Token;
use Raw\Component\OAuth2\Model\Client as BaseClient;

class Client extends BaseClient implements OwnableInterface
{
    public function getOwner()
    {
        return $this->user;
    }

    public function setOwner(User $owner = null)
    {
        $this->user = $owner;
        return $this;
    }

    public function hasOwner()
    {
        return $this->getOwner() !== null;
    }
}