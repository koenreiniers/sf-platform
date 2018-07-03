<?php
namespace Raw\Bundle\AdminBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\SubmitButtonTypeInterface;
use Symfony\Component\VarDumper\VarDumper;

class DeleteEntityType extends AbstractType
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * DeleteEntityType constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('delete', SubmitType::class, [
            'label' => 'Delete',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'delete_entity';
    }
}