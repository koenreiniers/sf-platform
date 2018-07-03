<?php
namespace Raw\Bundle\UserBundle\Normalizer;

use Raw\Bundle\UserBundle\Entity\Notification;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\VarDumper\VarDumper;

class NotificationNormalizer implements NormalizerInterface
{
    public function normalize($object, $format = null, array $context = array())
    {
        $data = [
            'id' => $object->getId(),
            'type' => $object->getType(),
            'message' => $object->getMessage(),
            'read' => $object->isRead(),
            'url' => $object->getUrl(),
        ];
        return $data;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Notification;
    }
}