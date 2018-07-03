<?php
namespace Raw\Component\OAuth\Token;

abstract class AbstractToken implements \Serializable
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $tokenSecret;

    public function serialize()
    {
        return serialize([
            $this->token,
            $this->tokenSecret,
        ]);
    }

    public function unserialize($serialized)
    {
        list($this->token, $this->tokenSecret) = unserialize($serialized);
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return AccessToken
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenSecret()
    {
        return $this->tokenSecret;
    }

    /**
     * @param string $tokenSecret
     * @return AccessToken
     */
    public function setTokenSecret($tokenSecret)
    {
        $this->tokenSecret = $tokenSecret;
        return $this;
    }


}