<?php
namespace Raw\Bundle\UserBundle\Behaviour;

use Raw\Bundle\UserBundle\Entity\User;

interface OwnableInterface
{
    /**
     * @return User|null
     */
    public function getOwner();

    /**
     * @param User|null $owner
     * @return $this
     */
    public function setOwner(User $owner = null);

    /**
     * @return bool
     */
    public function hasOwner();
}