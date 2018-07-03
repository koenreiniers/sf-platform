<?php
namespace Raw\Bundle\PlatformBundle\Form\TypeExtension;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityReturnIdentifierTypeExtension extends AbstractTypeExtension
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * EntityReturnIdentifierTypeExtension constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'return_identifier' => false,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!$options['return_identifier']) {
            return;
        }
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $identifier = $event->getData();
            if($identifier === null) {
                return;
            }
            $entityName = $event->getForm()->getConfig()->getOption('class');
            $entity = $this->entityManager->getRepository($entityName)->find($identifier);
            $event->setData($entity);

        });
        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){
            $entity = $event->getData();
            if(!$entity) {
                return;
            }
            $entityName = $event->getForm()->getConfig()->getOption('class');
            $identifier = $this->entityManager->getClassMetadata($entityName)->getIdentifierValues($entity);
            $event->setData($identifier);
        });
    }

    public function getExtendedType()
    {
        return EntityType::class;
    }
}