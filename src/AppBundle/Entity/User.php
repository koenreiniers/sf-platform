<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Raw\Bundle\UserBundle\Entity\User as BaseUser;

class User extends BaseUser
{
    /**
     * @var int
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }
}
