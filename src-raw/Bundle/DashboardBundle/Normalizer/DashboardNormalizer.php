<?php
namespace Raw\Bundle\DashboardBundle\Normalizer;

use Raw\Bundle\DashboardBundle\Entity\Dashboard;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DashboardNormalizer implements NormalizerInterface
{
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Dashboard;
    }
    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'default' => $object->isDefault(),
        ];
    }
}