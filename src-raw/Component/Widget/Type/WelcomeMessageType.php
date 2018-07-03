<?php
namespace Raw\Component\Widget\Type;


use Raw\Component\Widget\WidgetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WelcomeMessageType extends WidgetType
{
    public function buildSettingsForm(FormBuilderInterface $builder)
    {
        $builder->add('message', TextType::class);
    }

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'message' => '',
        ]);
    }

    public function getAlias()
    {
        return 'welcome_message';
    }
}