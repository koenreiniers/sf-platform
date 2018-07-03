<?php
namespace Raw\Component\Mage\Storage;

use Raw\Component\OAuth\Token\AccessToken;
use Raw\Component\OAuth\Token\RequestToken;

class NativeSessionStorage implements StorageInterface
{
    public function __construct()
    {
        if(session_state() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function setAccessToken(AccessToken $accessToken)
    {
        $_SESSION['access_token'] = serialize($accessToken);
    }

    public function setRequestToken(RequestToken $requestToken)
    {
        $_SESSION['request_token'] = serialize($requestToken);
    }

    public function getAccessToken()
    {
        return unserialize($_SESSION['access_token']);
    }

    public function getRequestToken()
    {
        return unserialize($_SESSION['request_token']);
    }
}