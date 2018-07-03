<?php
namespace Raw\Component\Bol\Plaza\Http;

use Raw\Component\Bol\Plaza\Http\Authentication\AuthenticationProviderInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\VarDumper\VarDumper;

class PlazaClient
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var AuthenticationProviderInterface
     */
    protected $authenticationProvider;

    /**
     * PlazaClient constructor.
     * @param AuthenticationProviderInterface $authenticationProvider
     * @param ClientInterface $httpClient
     */
    public function __construct(AuthenticationProviderInterface $authenticationProvider, ClientInterface $httpClient)
    {
        $this->authenticationProvider = $authenticationProvider;
        $this->httpClient = $httpClient;
    }

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function send(RequestInterface $request)
    {
        $request = $this->authenticationProvider->authenticate($request);

        $response = $this->httpClient->send($request);
        
        return $response;
    }

    public function delete($uri)
    {
        return $this->request('DELETE', $uri);
    }

    /**
     * @param string $uri
     *
     * @return ResponseInterface
     */
    public function get($uri)
    {
        return $this->request('GET', $uri);
    }

    /**
     * @param string $uri
     * @param string $body
     * @return ResponseInterface
     */
    public function post($uri, $body = '')
    {
        return $this->request('POST', $uri, $body);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param string $body
     *
     * @return ResponseInterface
     */
    public function request($method, $uri, $body = null)
    {
        $request = new Request($method, $uri, ['Content-Type' => 'application/xml'], $body);
        return $this->send($request);
    }
}