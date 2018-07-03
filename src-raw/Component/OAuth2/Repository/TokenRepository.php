<?php
namespace Raw\Component\OAuth2\Repository;

use Doctrine\ORM\EntityRepository;
use Raw\Component\OAuth2\Model\Token;
use Symfony\Component\Security\Core\User\UserInterface;

class TokenRepository extends EntityRepository
{
    public function findNonExpiredAccessToken(UserInterface $owner)
    {
        $qb = $this->createQueryBuilder('token')
            ->where('token.owner = :owner')
            ->andWhere('token.expiresAt >= :now')
            ->setMaxResults(1)
            ->setParameters([
                'owner' => $owner,
                'now' => new \DateTime(),
            ]);
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return Token[]
     */
    public function findExpiredTokens()
    {
        $qb = $this->createQueryBuilder('token')
            ->andWhere('token.expiresAt < :now')
            ->setMaxResults(1)
            ->setParameters([
                'now' => new \DateTime(),
            ]);
        return $qb->getQuery()->getResult();
    }
}