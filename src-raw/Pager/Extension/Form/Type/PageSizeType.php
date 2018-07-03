<?php
namespace Raw\Pager\Extension\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageSizeType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $sizes = [10, 25, 50, 100];
        $choices = [];
        foreach($sizes as $size) {
            $choices[$size] = $size;

        }
         $resolver->setDefaults([
             'choices' => $choices,
         ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}