<?php
namespace Raw\Component\Mage;

use GuzzleHttp\Psr7\Uri;
use Raw\Component\Mage\Storage\NativeSessionStorage;
use Raw\Component\Mage\Storage\StorageInterface;
use Raw\Component\OAuth\Consumer;
use Raw\Component\OAuth\ConsumerConfig;
use Symfony\Component\VarDumper\VarDumper;

class MageRestFactory
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * MageRestFactory constructor.
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage = null)
    {
        $storage = $storage !== null ? $storage : new NativeSessionStorage();
        $this->storage = $storage;
    }

    /**
     * @param $url
     * @param $consumerKey
     * @param $consumerSecret
     * @param $callbackUrl
     * @param StorageInterface|null $storage
     * @return MageRestFacade
     */
    public function create($url, $consumerKey, $consumerSecret, $callbackUrl, StorageInterface $storage = null)
    {
        $storage = $storage ?: $this->storage;

        $url = rtrim($url, ' /');
        $requestTokenUrl = $url.'/oauth/initiate';
        $authorizationUrl = $url.'/admin/oauth_authorize';
        $accessTokenUrl = $url.'/oauth/token';

        $consumerConfig = new ConsumerConfig($consumerKey, $consumerSecret, $requestTokenUrl, $accessTokenUrl, $authorizationUrl, $callbackUrl);
        $consumer = new Consumer($consumerConfig);

        $resourceUrl = new Uri($url.'/api/rest');
        $mageRest = new MageRest($resourceUrl, $consumer, $storage);

        return new MageRestFacade($mageRest);
    }
}