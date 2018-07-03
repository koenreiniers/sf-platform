<?php
namespace Raw\Bundle\DashboardBundle\Form\Type;

use Raw\Bundle\DashboardBundle\Entity\Dashboard;
use Raw\Bundle\DashboardBundle\Entity\Widget;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WidgetType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class);

        $builder->add('dashboard', EntityType::class, [
            'class' => Dashboard::class,
            'choice_label' => 'name',
        ]);
        $builder->add('settings', CollectionType::class, [
            'entry_type' => TextType::class,
            'allow_add' => true,
            'allow_delete' => true,
        ]);

        $builder->add('x', IntegerType::class);
        $builder->add('y', IntegerType::class);
        $builder->add('width', IntegerType::class);
        $builder->add('height', IntegerType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Widget::class,
        ]);
    }
}