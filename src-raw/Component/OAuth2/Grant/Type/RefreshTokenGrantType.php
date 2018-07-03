<?php
namespace Raw\Component\OAuth2\Grant\Type;

use Raw\Component\OAuth2\Grant\Exception\GrantException;
use Raw\Component\OAuth2\Grant;
use Raw\Component\OAuth2\Grant\GrantTypeInterface;
use Raw\Component\OAuth2\Model\Token;
use Raw\Component\OAuth2\Repository\TokenRepository;

class RefreshTokenGrantType implements GrantTypeInterface
{
    /**
     * @var TokenRepository
     */
    private $tokenRepository;

    /**
     * RefreshTokenGrantType constructor.
     * @param TokenRepository $tokenRepository
     */
    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function grant(Grant $grant)
    {
        $value = $grant->getArgument('refresh_token');

        /** @var Token $token */
        $token = $this->tokenRepository->findOneBy([
            'value' => $value,
            'type' => Token::TYPE_REFRESH
        ]);
        if($token === null) {
            throw $grant->createInvalidCredentialsException();
        }
        if($token->isExpired()) {
            throw $grant->createCredentialsExpiredException();
        }

        $grant->setUser($token->getOwner());
    }

    public function getName()
    {
        return 'refresh_token';
    }
}