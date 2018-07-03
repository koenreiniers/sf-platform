<?php
namespace Raw\Component\OAuth2;

use Raw\Component\OAuth2\Grant\Exception\CredentialsExpiredException;
use Raw\Component\OAuth2\Grant\Exception\GrantMissingParametersException;
use Raw\Component\OAuth2\Grant\Exception\InvalidCredentialsException;

class Grant
{
    /**
     * @var mixed
     */
    private $user;

    /**
     * @var bool
     */
    private $refreshToken = false;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var string
     */
    private $name;

    /**
     * Grant constructor.
     * @param string $type
     * @param array $arguments
     */
    public function __construct($type, array $arguments)
    {
        $this->name = $type;
        $this->arguments = $arguments;
    }

    public function createCredentialsExpiredException($message = 'The provided credentials have expired')
    {
        return new CredentialsExpiredException($message);
    }

    public function createInvalidCredentialsException($message = 'The provided credentials are invalid')
    {
        return new InvalidCredentialsException($message);
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument($name)
    {
        return isset($this->arguments[$name]);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws GrantMissingParametersException
     */
    public function getArgument($name)
    {
        if(!$this->hasArgument($name)) {
            throw new GrantMissingParametersException([$name]);
        }
        return $this->arguments[$name];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->name;
    }

    public function includeRefreshToken()
    {
        $this->refreshToken = true;
        return $this;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function isRefreshTokenIncluded()
    {
        return $this->refreshToken;
    }
}