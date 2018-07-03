<?php
namespace Raw\Bundle\DashboardBundle\Widget\Type;

use Raw\Component\Widget\WidgetType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChartWidgetType extends WidgetType
{
    public function buildSettingsForm(FormBuilderInterface $builder)
    {
        $builder->add('type', TextType::class);
        $builder->add('datasetNames', CollectionType::class, [
            'entry_type' => TextType::class,
        ]);
        $builder->add('parameters', CollectionType::class, [
            'entry_type' => TextType::class,
        ]);
        $builder->add('start', TextType::class);
        $builder->add('end', TextType::class);
    }

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type' => 'line',
            'datasetNames' => ['orders_by_status'],
        ]);
    }

    public function getAlias()
    {
        return 'chart';
    }
}