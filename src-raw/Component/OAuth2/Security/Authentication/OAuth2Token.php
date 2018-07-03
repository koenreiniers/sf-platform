<?php
namespace Raw\Component\OAuth2\Security\Authentication;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class OAuth2Token extends AbstractToken
{
    /**
     * @var string
     */
    public $accessTokenValue;

    /**
     * @return string
     */
    public function getAccessTokenValue()
    {
        return $this->accessTokenValue;
    }

    public function getCredentials()
    {
        return '';
    }
}