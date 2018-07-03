<?php
namespace Raw\Bundle\UserBundle\Behaviour;

use Raw\Bundle\UserBundle\Entity\User;

trait OwnableTrait
{
    /**
     * @var User|null
     */
    protected $owner;

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     * @return $this
     */
    public function setOwner(User $owner = null)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasOwner()
    {
        return $this->owner !== null;
    }
}