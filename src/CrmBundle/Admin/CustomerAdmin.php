<?php
namespace CrmBundle\Admin;

use CrmBundle\Form\Type\CustomerType;
use Doctrine\ORM\EntityManager;
use Raw\Component\Admin\Admin;
use Raw\Component\Admin\Easy\Element\FormElement;
use Raw\Component\Admin\Easy\Element\LayoutElement;
use Raw\Component\Sitemap\Map;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CrmBundle\Entity\Customer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CustomerAdmin extends Admin
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
        $this->setClassName(Customer::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'className' => Customer::class,
            'gridName' => 'customers_grid',
        ]);
    }

    public function buildPageMap(Map $map, UrlGeneratorInterface $urlGenerator, $entity)
    {
        $map
            ->add('List', 'list')
                ->add('View', $urlGenerator->generate('view', [
                    'id' => $entity->getId(),
                ]))
                    ->add('Edit', 'edit')->end()
                    ->add('Delete', 'delete')->end()
                ->end()
                ->add('Create', 'create')->end()
            ->end()
            ->add('Disabled customers', 'disabled')
            ;
    }

    public function buildCreateLayout(FormElement $layout, $entity, array $options)
    {
        $this->buildFormLayout($layout, $entity, $options);
    }
    public function buildListLayout(LayoutElement $layout, array $options)
    {
        $layout
            ->addGrid('customers_grid');
    }
    public function buildViewLayout(LayoutElement $layout, $entity, array $options)
    {
        $layout
            ->controller('CrmBundle:Customer:view', [
                'id' => $entity->getId(),
            ]);
    }
    public function buildEditLayout(FormElement $layout, $entity, array $options)
    {
        $this->buildFormLayout($layout, $entity, $options);
    }

    protected function buildFormLayout(FormElement $layout, $entity, array $options)
    {
        $layout
            ->setFormType(CustomerType::class)
            ->addTabset()
                ->addTab('General')
                    ->addField('firstName')
                ->endTab()
            ->endTabset()
            ;
    }
}