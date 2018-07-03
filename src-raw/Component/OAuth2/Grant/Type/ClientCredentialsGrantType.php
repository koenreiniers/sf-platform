<?php
namespace Raw\Component\OAuth2\Grant\Type;

use Doctrine\ORM\EntityRepository;
use Raw\Component\OAuth2\Grant\Exception\GrantException;
use Raw\Component\OAuth2\Grant;
use Raw\Component\OAuth2\Grant\GrantTypeInterface;
use Raw\Component\OAuth2\Model\Token;
use Raw\Component\OAuth2\Repository\ClientRepository;

class ClientCredentialsGrantType implements GrantTypeInterface
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * ClientCredentialsGrantType constructor.
     * @param ClientRepository $clientRepository
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function grant(Grant $grant)
    {
        $clientId = $grant->getArgument('client_id');
        $clientSecret = $grant->getArgument('client_secret');

        $client = $this->clientRepository->findOneByCredentials($clientId, $clientSecret);

        if($client === null) {
            throw $grant->createInvalidCredentialsException();
        }

        $grant->includeRefreshToken();
        $grant->setUser($client->getUser());
    }

    public function getName()
    {
        return 'client_credentials';
    }
}