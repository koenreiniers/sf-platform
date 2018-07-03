<?php
namespace Raw\Bundle\UserBundle\Admin;

use Raw\Bundle\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Raw\Bundle\UserBundle\Form\Type\UserType;
use Raw\Bundle\VersioningBundle\Entity\Version;
use Raw\Component\Admin\Admin;
use Raw\Component\Admin\AdminPage;
use Raw\Component\Admin\Easy\Actions;
use Raw\Component\Admin\Easy\Element\FormElement;
use Raw\Component\Admin\Easy\Element\GridElement;
use Raw\Component\Admin\Easy\Element\LayoutElement;
use Raw\Component\Admin\EasyAdmin;

use Raw\Component\Admin\Layout\Definition\Builder\ArrayNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\ContentNodeDefinition;
use Raw\Component\Layout\Action\Action;
use Raw\Component\Layout\Action\ActionCollectionBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAdmin extends Admin
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $userClass;

    /**
     * UserAdmin constructor.
     * @param EntityManager $entityManager
     * @param string $userClass
     */
    public function __construct(EntityManager $entityManager, $userClass = User::class)
    {
        $this->entityManager = $entityManager;
        $this->userClass = $userClass;
    }

    protected function configure()
    {
        $this->setClassName($this->userClass);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'className' => $this->userClass,
            'gridName' => 'users_grid',
        ]);
    }


    public function configurePages($builder)
    {
        $builder->add('history', function(AdminPage $page){
            $page
                ->enableActions(['view'])
                ->setController([$this, 'history'])

            ;
        });
    }

    public function history(ContentNodeDefinition $layoutNode, ArrayNodeDefinition $actions, $entity)
    {
        $layout = new LayoutElement();

        $actions
            ->children()
                ->actionNode('return')->end()
                ;

        $versions = $this->entityManager->getRepository(Version::class)->findByResource($entity);


        $layout
            ->template('RawAdminBundle:Admin:versions.html.twig', [
                'versions' => $versions,
            ]);

        $layout->toNode($layoutNode);

    }

    public function configureActions(ActionCollectionBuilder $actions)
    {
        $actions->callbackAdd('history', function(Action $action){
            $action
                ->setType('navigate')
                ->setLabel('History')
                ->setRoute('history')
                ->setRouteParametersResolver(function($entity){
                    return [
                        'id' => $entity->getId(),
                    ];
                })
            ;
        });
    }

    public function buildCreateLayout(FormElement $layout, $entity, array $options)
    {
        $this->buildFormLayout($layout, $entity, $options);
    }
    public function buildListLayout(LayoutElement $layout, array $options)
    {
        $layout
            ->addGrid('users_grid');
    }
    public function buildViewLayout(LayoutElement $layout, $entity, array $options)
    {

        $versions = $this->entityManager->getRepository(Version::class)->findByResource($entity);

        $layout
            ->template('RawUserBundle:User:view/content.html.twig', [
                'user' => $entity,
                'versions' => $versions,
            ]);
    }
    public function buildEditLayout(FormElement $layout, $entity, array $options)
    {
        $this->buildFormLayout($layout, $entity, $options);
    }

    protected function buildFormLayout(FormElement $layout, $entity, array $options)
    {
        $layout
            ->setFormType(UserType::class)
            ->addTabset()
                ->addTab('General')
                    ->addFieldset('General')
                        ->addField('username')
                        ->addField('email')
                        ->addField('enabled')
                    ->endFieldset()
                    ->addFieldset('Profile image')
                        ->addField('profileImage')
                    ->endFieldset()
                ->endTab()
                ->addTab('Personal')
                    ->addField('firstName')
                    ->addField('lastName')
                ->endTab()
                ->addTab('Security')
                    ->addField('groups')
                ->endTab()
            ->endTabset();
    }
}