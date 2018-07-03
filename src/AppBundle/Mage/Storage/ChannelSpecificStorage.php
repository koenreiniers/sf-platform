<?php
namespace AppBundle\Mage\Storage;

use AppBundle\Entity\Channel;
use Raw\Component\Mage\Storage\StorageInterface;
use Raw\Component\OAuth\Token\AccessToken;
use Raw\Component\OAuth\Token\RequestToken;

class ChannelSpecificStorage implements StorageInterface
{
    /**
     * @var Channel
     */
    private $channel;

    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    private function set($name, $value)
    {
        $this->channel->setParameter($name, $value);
    }

    private function get($name)
    {
        return $this->channel->getParameter($name);
    }

    public function getAccessToken()
    {
        return $this->get('access_token');
    }

    public function getRequestToken()
    {
        return $this->get('request_token');
    }

    public function setAccessToken(AccessToken $accessToken)
    {
        $this->set('access_token', $accessToken);
    }

    public function setRequestToken(RequestToken $requestToken)
    {
        $this->set('request_token', $requestToken);
    }
}