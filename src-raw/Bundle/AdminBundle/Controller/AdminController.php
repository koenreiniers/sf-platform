<?php
namespace Raw\Bundle\AdminBundle\Controller;

use Knp\Menu\Loader\ArrayLoader;
use Raw\Bundle\AdminBundle\Form\Type\DeleteEntityType;
use Raw\Bundle\PlatformBundle\Controller\AdvancedController;
use Raw\Component\Admin\Admin;
use Raw\Component\Admin\AdminEvents;
use Raw\Component\Admin\Easy\Element\FormElement;
use Raw\Component\Admin\Event\ResourceEvent;
use Raw\Component\Admin\Layout\Definition\ActionNode;
use Raw\Component\Admin\Layout\Definition\ArrayNode;
use Raw\Component\Admin\ResolvedAdmin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class AdminController extends AdvancedController
{

    /**
     * @param string $alias
     * @return ResolvedAdmin
     */
    private function getAdmin($alias)
    {
        $registry = $this->get('raw_admin.registry');
        return $registry->getAdmin($alias);
    }

    private function generateActions(ResolvedAdmin $admin, $verb, $entity = null)
    {
        if($verb === 'list') {
            $verb = 'list';
        }

        $verbOptions = $admin->getOption($verb);

        $actionNames = $verbOptions['actions'];

        $actions = [];
        foreach($actionNames as $actionName) {
            $action = $admin->getActions()[$actionName];
            $actions[] = $action->createView($entity);
        }
        return $actions;
    }

    public function pageAction(Request $request, $verb, $alias, $id)
    {
        $admin = $this->getAdmin($alias);
        $className = $admin->getClassName();

        $entity = $this->entityManager->find($className, $id);

        $this->denyAccessUnlessGranted($verb, $entity);

        $layout = $admin->createLayout($verb, $entity, $admin->getOptions());

        $actions = $this->generateActions($admin, $layout, $entity);

        return $this->renderVerb($admin, $verb, [
            'layout' => $layout,
            'entity' => $entity,
            'actions' => $actions,
        ]);
    }

    public function viewAction(Request $request, $alias, $id)
    {
        $verb = 'view';
        $admin = $this->getAdmin($alias);
        $className = $admin->getClassName();

        $entity = $this->entityManager->find($className, $id);

        $this->denyAccessUnlessGranted($verb, $entity);

        $layout = $admin->createLayout($verb, $entity);

        $actions = $this->generateActions($admin, $verb, $entity);

        return $this->renderVerb($admin, $verb, [
            'layout' => $layout,
            'entity' => $entity,
            'actions' => $actions,
        ]);
    }

    private function createBreadcrumbs(ResolvedAdmin $admin, $verb)
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        $children = [];

        if($verb !== 'list') {
            $children =  [
                'current' => [
                    'label' => ucfirst($verb),
                    'route' => $request->attributes->get('_route'),
                    'routeParameters' => $request->attributes->get('_route_params'),
                ]
            ];
        }

        $config = [
            'children' => [
                'list' => [
                    'label' => ucfirst($admin->getOption('plural')),
                    'route' => $admin->getRoute('list'),
                    'children' => $children,
                ],
            ],

        ];
        $loader = new ArrayLoader($this->get('knp_menu.factory'));
        return $loader->load($config);
    }

    private function renderVerb(ResolvedAdmin $admin, $verb, array $parameters = [])
    {
        $parameters = array_merge([
            'admin' => $admin,
            'breadcrumbs' => $this->createBreadcrumbs($admin, $verb),
        ], $parameters);
        return $this->render($admin->getTemplate($verb), $parameters);
    }

    public function deleteAction(Request $request, $alias, $id)
    {
        $verb = 'delete';
        $admin = $this->getAdmin($alias);
        $className = $admin->getClassName();

        $entity = $this->entityManager->find($className, $id);

        $this->denyAccessUnlessGranted($verb, $entity);

        $form = $this->createForm(DeleteEntityType::class, $entity);

        $form->handleRequest($request);

        if($form->isValid()) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
            $this->addFlash('success', ucfirst($admin->getOption('singular')).' has been deleted');
            return $this->redirect($admin->generateUrl('list'));
        }
        $layout = $admin->createLayout($verb, $entity);
        $actions = $this->generateActions($admin, $verb, $entity);


        return $this->renderVerb($admin, $verb, [
            'layout' => $layout,
            'entity' => $entity,
            'form' => $form->createView(),
            'actions' => $actions,
        ]);
    }

    public function listAction(Request $request, $alias)
    {
        $verb = 'list';
        $admin = $this->getAdmin($alias);
        $entity = null;

        $layout = $admin->createLayout($verb, $entity);

        $actions = $this->generateActions($admin, $verb, $entity);

        return $this->renderVerb($admin, $verb, [
            'admin' => $admin,
            'layout' => $layout,
            'entity' => null,
            'actions' => $actions,
        ]);
    }

    public function formAction(Request $request, ResolvedAdmin $admin, $verb, $entity = null)
    {
        $this->denyAccessUnlessGranted($verb, $entity);

        /** @var FormElement $layout */
        $layout = $admin->createLayout($verb, $entity);


        $form = $this->createForm($layout->getAttribute('formType'), $entity, $layout->getAttribute('formOptions'));

        $form->handleRequest($request);

        if($form->isValid()) {
            $entity = $form->getData();

            $this->get('event_dispatcher')->dispatch(AdminEvents::PRE_SAVE, new ResourceEvent($entity));

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            $this->get('event_dispatcher')->dispatch(AdminEvents::POST_SAVE, new ResourceEvent($entity));

            $this->addFlash('success', ucfirst($admin->getOption('singular')). ' has been saved');
            return $this->redirect($admin->generateUrl('view', [
                'id' => $entity->getId(),
            ]));
        }

        $actions = $this->generateActions($admin, $verb, $entity);

        return $this->renderVerb($admin, $verb, [
            'entity' => $entity,
            'layout' => $layout,
            'form' => $form->createView(),
            'actions' => $actions,
        ]);
    }

    public function createAction(Request $request, $alias)
    {
        $verb = 'create';
        $admin = $this->getAdmin($alias);
        $entity = $admin->createEntity();
        return $this->formAction($request, $admin, $verb, $entity);
    }

    public function editAction(Request $request, $alias, $id)
    {
        $verb = 'edit';
        $admin = $this->getAdmin($alias);
        $className = $admin->getClassName();
        $entity = $this->entityManager->find($className, $id);
        return $this->formAction($request, $admin, $verb, $entity);
    }
}