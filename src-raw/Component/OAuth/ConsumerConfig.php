<?php
namespace Raw\Component\OAuth;

class ConsumerConfig
{
    /**
     * @var string
     */
    private $consumerKey;

    /**
     * @var string
     */
    private $consumerSecret;

    /**
     * @var string
     */
    private $requestTokenUrl;

    /**
     * @var string
     */
    private $accessTokenUrl;

    /**
     * @var string
     */
    private $authorizationUrl;

    /**
     * @var string
     */
    private $callbackUrl;

    /**
     * ConsumerConfig constructor.
     * @param string $consumerKey
     * @param string $consumerSecret
     * @param string $requestTokenUrl
     * @param string $accessTokenUrl
     * @param string $authorizationUrl
     */
    public function __construct($consumerKey, $consumerSecret, $requestTokenUrl, $accessTokenUrl, $authorizationUrl, $callbackUrl)
    {
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->requestTokenUrl = $requestTokenUrl;
        $this->accessTokenUrl = $accessTokenUrl;
        $this->authorizationUrl = $authorizationUrl;
        $this->callbackUrl = $callbackUrl;
    }

    /**
     * @return string
     */
    public function getConsumerKey()
    {
        return $this->consumerKey;
    }

    /**
     * @return string
     */
    public function getConsumerSecret()
    {
        return $this->consumerSecret;
    }

    /**
     * @return string
     */
    public function getRequestTokenUrl()
    {
        return $this->requestTokenUrl;
    }

    /**
     * @return string
     */
    public function getAccessTokenUrl()
    {
        return $this->accessTokenUrl;
    }

    /**
     * @return string
     */
    public function getAuthorizationUrl()
    {
        return $this->authorizationUrl;
    }

    /**
     * @return string
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }


}