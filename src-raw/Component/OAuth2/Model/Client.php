<?php
namespace Raw\Component\OAuth2\Model;

use Raw\Bundle\UserBundle\Behaviour\OwnableInterface;
use Raw\Bundle\UserBundle\Behaviour\OwnableTrait;
use Symfony\Component\Security\Core\User\UserInterface;

class Client
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $publicId;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     * @return Client
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getPublicId()
    {
        return $this->publicId;
    }

    /**
     * @param string $publicId
     * @return Client
     */
    public function setPublicId($publicId)
    {
        $this->publicId = $publicId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     * @return Client
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }
}