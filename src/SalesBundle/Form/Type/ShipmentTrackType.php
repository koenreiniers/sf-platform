<?php
namespace SalesBundle\Form\Type;

use ShippingBundle\Entity\Carrier;
use SalesBundle\Entity\ShipmentTrack;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShipmentTrackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('carrier', EntityType::class, [
            'class' => Carrier::class,
            'choice_label' => 'code',
        ]);
        $builder->add('title', TextType::class);
        #$builder->add('trackingNumber', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShipmentTrack::class,
        ]);
    }
}