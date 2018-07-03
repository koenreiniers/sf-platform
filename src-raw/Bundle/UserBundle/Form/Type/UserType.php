<?php
namespace Raw\Bundle\UserBundle\Form\Type;

use Raw\Bundle\UserBundle\Entity\User;
use Raw\Bundle\AdminBundle\Form\Type\DeleteEntityType;
use Raw\Bundle\PlatformBundle\Form\Type\UploadFileType;
use Raw\Bundle\UserBundle\Entity\UserGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\VarDumper\VarDumper;

class UserType extends AbstractType
{
    /**
     * @var string
     */
    private $userClass;

    /**
     * UserType constructor.
     * @param string $userClass
     */
    public function __construct($userClass = User::class)
    {
        $this->userClass = $userClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('username', TextType::class);
        $builder->add('email', EmailType::class);
        $builder->add('enabled', CheckboxType::class, [
            'required' => false,
        ]);

        $builder->add('profileImage', UploadFileType::class);

        $builder->add('firstName', TextType::class);
        $builder->add('lastName', TextType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            /** @var User $user */
            $user = $event->getData();

            if($user !== null && $user->getId() !== null) {
                $form = $event->getForm();
                $form->add('username', TextType::class, [
                    'disabled' => true,
                ]);
            }
        });
        $builder->add('groups', EntityType::class, [
            'multiple' => true,
            'choice_label' => 'name',
            'class' => UserGroup::class,
            'required' => false,
        ]);
        #$builder->add('plainPassword', PasswordType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->userClass,
        ]);
    }
}