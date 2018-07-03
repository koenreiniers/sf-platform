<?php
namespace Raw\Component\OAuth;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Raw\Component\OAuth\Signature\HmacSignature;
use Raw\Component\OAuth\Signature\PlaintextSignature;
use Raw\Component\OAuth\Token\AccessToken;
use Raw\Component\OAuth\Token\RequestToken;
use Symfony\Component\VarDumper\VarDumper;

class Consumer
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var ConsumerConfig
     */
    private $config;

    /**
     * @var HmacSignature
     */
    private $signer;

    /**
     * @var string
     */
    private $signatureMethod = 'PLAINTEXT';//'HMAC-SHA1';

    /**
     * Consumer constructor.
     * @param ConsumerConfig $config
     */
    public function __construct(ConsumerConfig $config, $signer = null)
    {
        $this->config = $config;
        $this->httpClient = new Client();
        $this->signer = $signer ?: new PlaintextSignature();//new HmacSignature();
    }

    /**
     * @return int
     */
    public function generateNonce()
    {
        return rand(0,999999999);
    }

    /**
     * @return int
     */
    private function getTimestamp()
    {
        return time();
    }

    /**
     * Redirect to authorization page
     *
     * @param RequestToken $requestToken
     */
    public function startAuthorization(RequestToken $requestToken)
    {
        $redirectUrl = $this->config->getAuthorizationUrl().'?oauth_token='.$requestToken->getToken();
        header('Location: '.$redirectUrl);
        die;
    }

    /**
     * @param RequestInterface $request
     * @param array $params
     * @param null $tokenSecret
     * @return RequestInterface|static
     */
    public function signRequest(RequestInterface $request, array $params, $tokenSecret = null)
    {
        $params = array_merge($this->getDefaultSignatureParameters(), $params);
        ksort($params);

        $url = (string)$request->getUri();//->withQuery('')->withFragment('');

        $signature = $this->signer->sign($params, $request->getMethod(), $url, $this->config->getConsumerSecret(), $tokenSecret);

        $authorizationParams = array_merge([
            'OAuth realm' => '',
        ], $params);


        $authorizationParams['oauth_signature'] = $signature;

        foreach($authorizationParams as $key => $value) {
            $request = $request->withAddedHeader('Authorization', $key.'="'.$value.'"');
        }

        return $request;
    }

    /**
     * @param RequestInterface $request
     * @param AccessToken $accessToken
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send(RequestInterface $request, AccessToken $accessToken)
    {
        $stringToken = $accessToken->getToken();

        $sigParams = [
            'oauth_token' => $stringToken,
        ];

        $request = $this->signRequest($request, $sigParams, $accessToken->getTokenSecret());


        $request = $request->withHeader('Accept', 'application/json');
        $request = $request->withHeader('Content-Type', 'application/json');
        
        $response = $this->httpClient->send($request);

        return $response;
    }

    private function getDefaultSignatureParameters()
    {
        return [
            'oauth_consumer_key' => $this->config->getConsumerKey(),
            'oauth_nonce' => $this->generateNonce(),
            'oauth_timestamp' => $this->getTimestamp(),
            'oauth_version' => '1.0',
            'oauth_signature_method' => $this->signatureMethod,
        ];
    }

    /**
     * @param RequestToken $requestToken
     * @return AccessToken
     */
    public function getAccessToken(RequestToken $requestToken)
    {
        $verifier = $_GET['oauth_verifier'];
        $method = 'POST';

        $sigParams = [
            'oauth_token' => $requestToken->getToken(),
            'oauth_verifier' => $verifier,
        ];

        $url = $this->config->getAccessTokenUrl();

        $request = new Request($method, $url);

        $request = $this->signRequest($request, $sigParams, $requestToken->getTokenSecret());

        $request = $request->withHeader('Accept', 'application/json');

        $response = $this->httpClient->send($request);

        $responseBody = (string)$response->getBody();

        $params = [];

        parse_str($responseBody, $params);

        $accessToken = new AccessToken();
        $accessToken->setToken($params['oauth_token']);
        $accessToken->setTokenSecret($params['oauth_token_secret']);
        return $accessToken;
    }

    /**
     * @return RequestToken
     */
    public function getRequestToken()
    {

        $url = $this->config->getRequestTokenUrl();
        $request = new Request('POST', $url);

        $sigParams = [
            'oauth_callback' => $this->config->getCallbackUrl(),
        ];
        $request = $this->signRequest($request, $sigParams);

        $response = $this->httpClient->send($request);

        $responseBody = (string)$response->getBody();
        $params = [];
        parse_str($responseBody, $params);

        $reqToken = new RequestToken();
        $reqToken->setToken($params['oauth_token']);
        $reqToken->setTokenSecret($params['oauth_token_secret']);

        return $reqToken;
    }
}