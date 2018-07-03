<?php
namespace Raw\Bundle\UserBundle\Security\Voter;

use Raw\Bundle\UserBundle\Behaviour\OwnableInterface;
use Raw\Bundle\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class SuperAdminVoter implements VoterInterface
{
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        if($token->getUser() instanceof User && $token->getUser()->isSuperAdmin()) {
            return VoterInterface::ACCESS_GRANTED;
        }
        return VoterInterface::ACCESS_ABSTAIN;
    }
}