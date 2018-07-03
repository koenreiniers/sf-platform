<?php
namespace SalesBundle\Form\Type;

use SalesBundle\Entity\Shipment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShipmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('shipmentItems', CollectionType::class, [
            'allow_add' => false,
            'allow_delete' => false,
            'entry_type' => ShipmentItemType::class,
        ]);
        $builder->add('shipmentTracks', CollectionType::class, [
            'allow_add' => true,
            'allow_delete' => true,
            'entry_type' => ShipmentTrackType::class,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Shipment::class,
        ]);
    }
}