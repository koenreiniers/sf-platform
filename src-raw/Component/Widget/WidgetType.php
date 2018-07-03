<?php
namespace Raw\Component\Widget;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class WidgetType
{

    public function buildSettingsForm(FormBuilderInterface $builder)
    {

    }

    public function configureSettings(OptionsResolver $resolver)
    {

    }

    /**
     * @return string
     */
    abstract public function getAlias();
}