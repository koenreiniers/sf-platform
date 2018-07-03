<?php
namespace Raw\Component\OAuth2\Repository;

use Doctrine\ORM\EntityRepository;
use Raw\Component\OAuth2\Model\Client;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientRepository extends EntityRepository
{
    /**
     * @param string $clientId
     * @param string $clientSecret
     * @return Client|object
     */
    public function findOneByCredentials($clientId, $clientSecret)
    {
        return $this->findOneBy([
            'publicId' => $clientId,
            'secret' => $clientSecret,
        ]);
    }
}