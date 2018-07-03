<?php
namespace Raw\Bundle\UserBundle\Normalizer;

use AppBundle\Entity\User;
use Raw\Bundle\UserBundle\Entity\UserGroup;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface, DenormalizerInterface
{

    /**
     * @param User $object
     * @inheritdoc
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $user = $object;

        $data = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'enabled' => $user->isEnabled(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'roles' => $user->getRoles(),
            'groups' => $user->getGroupCodes(),
        ];
        return $data;
    }

    public function denormalize($data, $class, $format = null, array $context = array())
    {
        $object = isset($context['object']) ? $context['object'] : new $class;

        global $kernel;

        $em = $kernel->getContainer()->get('doctrine.orm.default_entity_manager');

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach($data as $key => $value) {

            if($key === 'id') {
                continue;
            }
            if($key === 'groups') {
                $groupCodes = $value;
                $value = [];
                foreach($groupCodes as $groupCode) {
                    $value[] = $em->getRepository(UserGroup::class)->findOneBy(['code' => $groupCode]);
                }
            }


            $accessor->setValue($object, $key, $value);

        }



        return $object;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === User::class;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof User;
    }
}