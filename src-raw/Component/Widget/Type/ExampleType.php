<?php
namespace Raw\Component\Widget\Type;


use Raw\Component\Widget\WidgetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExampleType extends WidgetType
{
    public function buildSettingsForm(FormBuilderInterface $builder)
    {

    }

    public function configureSettings(OptionsResolver $resolver)
    {

    }

    public function getAlias()
    {
        return 'example';
    }
}