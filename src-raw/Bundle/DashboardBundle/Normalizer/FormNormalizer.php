<?php
namespace Raw\Bundle\DashboardBundle\Normalizer;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\VarDumper\VarDumper;

class FormNormalizer implements NormalizerInterface
{
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FormInterface;
    }
    public function normalize($object, $format = null, array $context = array())
    {
        /** @var FormInterface $form */
        $form = $object;

        $typeName = 'text';


        $innerType = $form->getConfig()->getType()->getInnerType();

        if($innerType instanceof IntegerType) {
            $typeName = 'integer';
        }

        $data = [
            'type' => $typeName,
            //'name' => $form->getName(),
            'required' => $form->isRequired(),
        ];
        if(count($form) > 0) {
            $data['type'] = 'form';
            $children = [];
            foreach($form->all() as $childName => $childForm) {
                $children[$childName] = $this->normalize($childForm);
            }
            $data['children'] = $children;
        }

        return $data;
    }
}