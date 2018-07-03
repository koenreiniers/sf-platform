<?php
namespace Raw\Bundle\GridBundle\Widget\Type;

use Raw\Component\Widget\WidgetType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GridWidgetType extends WidgetType
{
    public function buildSettingsForm(FormBuilderInterface $builder)
    {
        $builder->add('gridName', ChoiceType::class, [
            'choices' => [
                'users' => 'users',
            ],
        ]);
    }

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'gridName' => null,
        ]);
    }

    public function getAlias()
    {
        return 'grid';
    }
}