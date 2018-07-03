<?php
namespace Raw\Bundle\ApiBundle\Normalizer;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FormErrorIteratorNormalizer implements NormalizerInterface
{
    /**
     * @param FormErrorIterator $object
     * @inheritdoc
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $data = [];

        $form = $object->getForm();

        foreach($form->all() as $childName => $childForm) {

            $errors = $childForm->getErrors();

            $errorMessages = [];

            foreach($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            $data[$childName] = $errorMessages;

            $data = array_merge($data, $this->normalize($childForm->getErrors()));

        }

        return $data;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FormErrorIterator;
    }
}