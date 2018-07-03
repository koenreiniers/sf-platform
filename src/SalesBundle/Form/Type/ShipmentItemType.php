<?php
namespace SalesBundle\Form\Type;

use Platform\PlatformHelper;
use Platform\PlatformRegistry;
use SalesBundle\Entity\ShipmentItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShipmentItemType extends AbstractType
{
    /**
     * @var PlatformHelper
     */
    private $platformHelper;

    /**
     * ShipmentItemType constructor.
     * @param PlatformHelper $platformHelper
     */
    public function __construct(PlatformHelper $platformHelper)
    {
        $this->platformHelper = $platformHelper;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('qty', TextType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            /** @var ShipmentItem $shipmentItem */
            $shipmentItem = $event->getData();

            $channel = $shipmentItem->getShipment()->getOrder()->getStore()->getChannel();

            $form = $event->getForm();
            $platform = $this->platformHelper->getAdapter($channel);
            if(!$platform->allowPartialShipments()) {
                $this->disableField($form->get('qty'));
            }
        });
    }

    private function disableField(FormInterface $field){
        $parent = $field->getParent();
        $options = $field->getConfig()->getOptions();
        $name = $field->getName();
        $type = get_class($field->getConfig()->getType()->getInnerType());
        $parent->remove($name);
        $parent->add($name, $type, array_merge($options,['disabled' => true]));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShipmentItem::class,
        ]);
    }
}