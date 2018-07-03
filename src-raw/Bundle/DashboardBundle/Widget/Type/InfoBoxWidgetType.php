<?php
namespace Raw\Bundle\DashboardBundle\Widget\Type;

use Raw\Component\Widget\WidgetType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoBoxWidgetType extends WidgetType
{
    public function buildSettingsForm(FormBuilderInterface $builder)
    {
        $builder->add('color', TextType::class);
        $builder->add('statisticName', TextType::class);
        $builder->add('icon', TextType::class);
    }

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'color' => 'green',
            'icon' => 'circle',
            'statisticName' => null,
        ]);
    }

    public function getAlias()
    {
        return 'info_box';
    }
}