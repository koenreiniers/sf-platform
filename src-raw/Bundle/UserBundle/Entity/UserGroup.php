<?php
namespace Raw\Bundle\UserBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("code")
 */
class UserGroup extends BaseGroup
{
    /**
     * @var array
     */
    protected $roles = [];

    /**
     * @var string
     */
    protected $code;

    public function __toString()
    {
        if($this->id === null) {
            return 'New user group';
        }
        return $this->name;
    }

    public function __construct($name = null, array $roles = [])
    {
        parent::__construct($name, $roles);
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return UserGroup
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }


}