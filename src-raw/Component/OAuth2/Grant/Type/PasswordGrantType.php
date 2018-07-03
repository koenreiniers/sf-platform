<?php
namespace Raw\Component\OAuth2\Grant\Type;

use Raw\Component\OAuth2\Grant\Exception\GrantException;
use Raw\Component\OAuth2\Grant;
use Raw\Component\OAuth2\Grant\GrantTypeInterface;
use Raw\Component\OAuth2\Model\Token;
use Raw\Component\OAuth2\Repository\TokenRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class PasswordGrantType implements GrantTypeInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * PasswordGrantType constructor.
     * @param UserProviderInterface $userProvider
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(UserProviderInterface $userProvider, EncoderFactoryInterface $encoderFactory)
    {
        $this->userProvider = $userProvider;
        $this->encoderFactory = $encoderFactory;
    }


    public function grant(Grant $grant)
    {
        $username = $grant->getArgument('username');
        $password = $grant->getArgument('password');

        try {
            $user = $this->userProvider->loadUserByUsername($username);
        } catch(UsernameNotFoundException $e) {
            throw $grant->createInvalidCredentialsException('Invalid username');
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        $valid = $encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());

        if(!$valid) {
            throw $grant->createInvalidCredentialsException('Invalid password');
        }

        $grant->includeRefreshToken();
        $grant->setUser($user);
    }

    public function getName()
    {
        return 'password';
    }
}