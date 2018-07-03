<?php
namespace Raw\Bundle\UserBundle\Form\Type;

use Raw\Bundle\UserBundle\Entity\UserGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserGroupType extends AbstractType
{
    /**
     * @var string
     */
    private $userGroupClass;

    /**
     * UserGroupType constructor.
     * @param string $userGroupClass
     */
    public function __construct($userGroupClass = UserGroup::class)
    {
        $this->userGroupClass = $userGroupClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);
        $builder->add('code', TextType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            if($event->getData()->getId() === null) {
                return;
            }
            $form = $event->getForm();
            $form->add('code', TextType::class, [
                'disabled' => true,
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->userGroupClass,
        ]);
    }
}