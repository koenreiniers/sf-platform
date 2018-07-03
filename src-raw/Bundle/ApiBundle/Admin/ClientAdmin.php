<?php
namespace Raw\Bundle\ApiBundle\Admin;

use Doctrine\ORM\EntityManager;
use Raw\Bundle\ApiBundle\Entity\Client;
use Raw\Bundle\ApiBundle\Form\Type\ClientType;
use Raw\Component\Admin\Admin;
use Raw\Component\Admin\Easy\Element\FormElement;
use Raw\Component\Admin\Easy\Element\LayoutElement;
use Raw\Component\Admin\Layout\Definition\Builder\ArrayNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\ContentNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\FormNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientAdmin extends Admin
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
        $this->setClassName(Client::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'className' => Client::class,
            'gridName' => 'api_clients',
        ]);
    }

    public function buildListLayout(LayoutElement $layout, array $options)
    {
        $layout
            ->addGrid('api_clients');
    }

    public function buildCreateLayout(FormElement $layout, $entity, array $options)
    {
        $this->buildFormLayout($layout, $entity, $options);
    }

    public function buildEditLayout(FormElement $layout, $entity, array $options)
    {
        $this->buildFormLayout($layout, $entity, $options);
    }


    protected function buildFormLayout(FormElement $layout, $entity, array $options)
    {
        $layout
            ->setFormType(ClientType::class)
            ->addTabset()
                ->addTab('General')
                    ->addField('publicId')
                    ->addField('secret')
                ->endTab()
            ->endTabset();
    }


    public function buildViewLayout(LayoutElement $layout, $entity, array $options)
    {
        $layout
            ->addTabset()
                ->addTab('General')
                    ->template('RawAdminBundle:Admin:properties.html.twig', [
                        'properties' => ['publicId', 'secret'],
                        'entity' => $entity,
                    ])
                ->endTab()
            ->endTabset()
            ;
    }
}