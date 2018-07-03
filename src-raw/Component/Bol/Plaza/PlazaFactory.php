<?php
namespace Raw\Component\Bol\Plaza;

use Raw\Component\Bol\Plaza\Http\Authentication\AuthenticationProvider;
use Raw\Component\Bol\Plaza\Http\PlazaClient;
use GuzzleHttp\Client;

class PlazaFactory
{
    /**
     * @param string $publicKey
     * @param string $privateKey
     * @param bool $testMode
     *
     * @return Plaza
     */
    public static function create($publicKey, $privateKey, $testMode = false)
    {
        $uri = $testMode ? 'https://test-plazaapi.bol.com' : 'https://plazaapi.bol.com';
        $httpClient = new Client([
            'base_uri' => $uri,
        ]);
        $plazaCredentials = new PlazaCredentials($publicKey, $privateKey);
        $authenticationProvider = new AuthenticationProvider($plazaCredentials);
        $plazaClient = new PlazaClient($authenticationProvider, $httpClient);
        $plaza = new Plaza($plazaClient);
        return $plaza;
    }
}