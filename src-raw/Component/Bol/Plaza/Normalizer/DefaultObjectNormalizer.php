<?php
namespace Raw\Component\Bol\Plaza\Normalizer;

use Raw\Component\Bol\Plaza\Model\ShipmentRequest;
use Raw\Component\Bol\Plaza\Model\Transport;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DefaultObjectNormalizer implements NormalizerInterface
{

    /**
     * @var callable
     */
    private $callbacks = [];

    /**
     * @var array
     */
    private $propertyMap = [];

    public function setCallback($property, callable $callback)
    {
        $this->callbacks[$property] = $callback;
        return $this;
    }

    public function setPropertyMap(array $map)
    {
        $this->propertyMap = $map;
        return $this;
    }

    protected function init($object, $format = null, array $context = array())
    {

    }

    public function normalize($object, $format = null, array $context = array())
    {
        $this->init($object, $format, $context);

        $normalized = [];
        $rc = new \ReflectionClass(get_class($object));
        foreach($this->propertyMap as $to => $from) {
            $rp = $rc->getProperty($from);
            $rp->setAccessible(true);
            $value = $rp->getValue($object);

            if(isset($this->callbacks[$from])) {
                $callback = $this->callbacks[$from];
                $value = $callback($value);
            }
            $normalized[$to] = $value;
        }
        return $normalized;
    }

    public function supportsNormalization($data, $format = null)
    {
        return is_object($data);
    }
}