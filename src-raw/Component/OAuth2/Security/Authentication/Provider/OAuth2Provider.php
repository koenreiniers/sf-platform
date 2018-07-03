<?php
namespace Raw\Component\OAuth2\Security\Authentication\Provider;

use Raw\Component\OAuth2\Model\Token;
use Raw\Component\OAuth2\Repository\TokenRepository;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Raw\Component\OAuth2\Security\Authentication\OAuth2Token;

class OAuth2Provider implements AuthenticationProviderInterface
{
    /**
     * @var TokenRepository
     */
    private $tokenRepository;

    /**
     * OAuth2Provider constructor.
     * @param TokenRepository $tokenRepository
     */
    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * @param OAuth2Token $token
     *
     * @inheritdoc
     */
    public function authenticate(TokenInterface $token)
    {
        $accessTokenValue = $token->getAccessTokenValue();

        $accessToken = $this->tokenRepository->findOneBy([
            'value' => $accessTokenValue,
            'type' => Token::TYPE_ACCESS,
        ]);

        if($accessToken === null) {
            throw new AuthenticationException('Invalid access token');
        }

        if($accessToken->isExpired()) {
            throw new AuthenticationException('Access token has expired');
        }

        $user = $accessToken->getOwner();

        $authenticatedToken = new OAuth2Token($user->getRoles());
        $authenticatedToken->setAuthenticated(true);
        $authenticatedToken->setUser($user);
        return $authenticatedToken;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof OAuth2Token;
    }
}