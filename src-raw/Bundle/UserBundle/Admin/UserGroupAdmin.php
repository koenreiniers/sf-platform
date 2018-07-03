<?php
namespace Raw\Bundle\UserBundle\Admin;

use Doctrine\ORM\EntityManager;
use Raw\Bundle\UserBundle\Entity\UserGroup;
use Raw\Bundle\UserBundle\Form\Type\UserGroupType;
use Raw\Component\Admin\Admin;
use Raw\Component\Admin\Easy\Actions;
use Raw\Component\Admin\Easy\Element\FormElement;
use Raw\Component\Admin\Easy\Element\GridElement;
use Raw\Component\Admin\Easy\Element\LayoutElement;
use Raw\Component\Admin\EasyAdmin;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserGroupAdmin extends Admin
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UserAdmin constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setClassName(UserGroup::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'className' => UserGroup::class,
            'gridName' => 'user_groups_grid',
        ]);
    }

    public function buildListLayout(LayoutElement $layout, array $options)
    {
        $layout
            ->addGrid('user_groups_grid');
    }

    public function buildEditLayout(FormElement $layout, $entity, array $options)
    {
        $this->buildFormLayout($layout, $entity, $options);
    }
    public function buildCreateLayout(FormElement $layout, $entity, array $options)
    {
        $this->buildFormLayout($layout, $entity, $options);
    }

    protected function buildFormLayout(FormElement $layout, $entity, array $options)
    {
        $layout
            ->setFormType(UserGroupType::class)
            ->addTabset()
                ->addTab('General')
                    ->addFieldset('General')
                        ->addField('code')
                        ->addField('name')
                    ->endFieldset()
                ->endTab()
            ->endTabset();
    }


    public function buildViewLayout(LayoutElement $layout, $entity, array $options)
    {


        $layout
            ->addTabset()
                ->addTab('General')
                    ->template('RawAdminBundle:Admin:properties.html.twig', [
                        'properties' => ['code', 'name'],
                        'entity' => $entity,
                    ])
                ->endTab()
            ->endTabset()
        ;
    }
}