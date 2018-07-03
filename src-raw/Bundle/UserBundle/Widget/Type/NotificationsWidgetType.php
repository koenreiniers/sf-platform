<?php
namespace Raw\Bundle\UserBundle\Widget\Type;

use Raw\Component\Widget\WidgetType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationsWidgetType extends WidgetType
{
    public function buildSettingsForm(FormBuilderInterface $builder)
    {
        $builder->add('limit', IntegerType::class);
    }

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'limit' => 10,
        ]);
    }

    public function getAlias()
    {
        return 'notifications';
    }
}