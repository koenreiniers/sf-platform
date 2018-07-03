<?php
namespace Raw\Component\OAuth2\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\VarDumper\VarDumper;
use Raw\Component\OAuth2\Security\Authentication\OAuth2Token;

class OAuth2Listener implements ListenerInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuthenticationManagerInterface
     */
    private $authenticationManager;

    /**
     * OAuth2Listener constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param AuthenticationManagerInterface $authenticationManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }


    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if(!$request->headers->has('Authorization')) {
            return;
        }

        $authorizationHeader = $request->headers->get('Authorization');

        $prefix = 'Bearer';

        if(strpos($authorizationHeader, $prefix) !== 0) {
            return;
        }

        $tokenValue = trim(substr($authorizationHeader, strlen($prefix)));

        $token = new OAuth2Token();
        $token->accessTokenValue = $tokenValue;


        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);

            return;
        } catch (AuthenticationException $failed) {
            // ... you might log something here

            // To deny the authentication clear the token. This will redirect to the login page.
            // Make sure to only clear your token, not those of other authentication listeners.
            // $token = $this->tokenStorage->getToken();
            // if ($token instanceof WsseUserToken && $this->providerKey === $token->getProviderKey()) {
            //     $this->tokenStorage->setToken(null);
            // }
            // return;
        }

        // By default deny authorization
        $response = new Response('Invalid access token');
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);

    }
}