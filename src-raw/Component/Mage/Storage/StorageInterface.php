<?php
namespace Raw\Component\Mage\Storage;

use Raw\Component\OAuth\Token\AccessToken;
use Raw\Component\OAuth\Token\RequestToken;

interface StorageInterface
{

    /**
     * @param AccessToken $accessToken
     */
    public function setAccessToken(AccessToken $accessToken);

    /**
     * @param RequestToken $requestToken
     */
    public function setRequestToken(RequestToken $requestToken);

    /**
     * @return AccessToken
     */
    public function getAccessToken();

    /**
     * @return RequestToken
     */
    public function getRequestToken();
}