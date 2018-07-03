<?php
namespace Raw\Bundle\UserBundle\Security\Voter;

use Raw\Bundle\UserBundle\Behaviour\OwnableInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class EntityOwnerVoter implements VoterInterface
{
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        if(!$subject instanceof OwnableInterface || !$subject->hasOwner()) {
            return VoterInterface::ACCESS_ABSTAIN;
        }
        if($token->getUser() === $subject->getOwner()) {
            return VoterInterface::ACCESS_GRANTED;
        }
        return VoterInterface::ACCESS_DENIED;
    }
}