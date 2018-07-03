<?php
namespace Raw\Component\Mage;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Raw\Component\Mage\Storage\StorageInterface;
use Raw\Component\OAuth\Consumer;
use Raw\Component\OAuth\Token\RequestToken;

class MageRest
{
    /**
     * @var Uri
     */
    private $resourceUrl;

    /**
     * @var Consumer
     */
    private $consumer;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * MageRest constructor.
     * @param Uri $resourceUrl
     * @param Consumer $consumer
     * @param StorageInterface $storage
     */
    public function __construct(Uri $resourceUrl, Consumer $consumer, StorageInterface $storage)
    {
        $this->resourceUrl = $resourceUrl;
        $this->consumer = $consumer;
        $this->storage = $storage;
    }

    public function get($url)
    {
        return $this->request('GET', $url);
    }

    public function post($url, $body = null)
    {
        return $this->request('POST', $url, $body);
    }

    public function put($url, $body = null)
    {
        return $this->request('PUT', $url, $body);
    }

    public function isAuthorized()
    {
        return $this->storage->getRequestToken() !== null;
    }

    public function acquireRequestToken()
    {
        $requestToken = $this->consumer->getRequestToken();

        $this->storage->setRequestToken($requestToken);
    }

    public function redirectForAuthorization()
    {
        $requestToken = $this->storage->getRequestToken();
        $this->consumer->startAuthorization($requestToken);
    }

    public function acquireAccessToken()
    {
        $requestToken = $this->storage->getRequestToken();

        $accessToken = $this->consumer->getAccessToken($requestToken);

        $this->storage->setAccessToken($accessToken);
    }

    /**
     * @param string $method
     * @param string $url
     * @param null $body
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request($method, $url, $body = null)
    {
        $url = $url instanceof Uri ? $url : new Uri($url);
        $url = $this->createAbsoluteUrl($url);
        $request = new Request($method, $url, [], $body);
        return $this->send($request);
    }

    private function createAbsoluteUrl(Uri $url)
    {
        if(Uri::isAbsolute($url)) {
            return $url;
        }
        $url = new Uri($url);
        $url = $url->withScheme($this->resourceUrl->getScheme());
        $url = $url->withHost($this->resourceUrl->getHost());
        $url = $url->withPath($this->resourceUrl->getPath().'/'.$url->getPath());
        return $url;
    }


    public function send(Request $request)
    {
        $response = $this->consumer->send($request, $this->storage->getAccessToken());
        return $response;
    }
}