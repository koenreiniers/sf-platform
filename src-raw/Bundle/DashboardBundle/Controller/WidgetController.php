<?php
namespace Raw\Bundle\DashboardBundle\Controller;

use Raw\Bundle\DashboardBundle\Entity\Widget;
use Raw\Bundle\DashboardBundle\Form\Type\WidgetType;
use Raw\Bundle\PlatformBundle\Controller\AdvancedController;
use Raw\Component\Widget\WidgetFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class WidgetController extends AdvancedController
{
    /**
     * @var WidgetFactory
     */
    private $widgetFactory;

    protected function getClassName()
    {
        return Widget::class;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->widgetFactory = $container->get('raw_dashboard.widget_factory');
    }

    public function indexAction(Request $request)
    {
        $qb = $this->createQueryBuilder('widget')
            ->join('widget.dashboard', 'dashboard')
            ->where('dashboard.owner = :owner')
            ->setParameter('owner', $this->getUser());

        return $qb->getQuery()->getArrayResult();
    }

    public function viewAction(Request $request, $id)
    {
        $widget = $this->findOr404($id);

        $this->denyAccessUnlessGranted('view', $widget);

        return [
            'id' => $widget->getId(),
            'dashboard' => $widget->getDashboard()->getId(),
            'title' => $widget->getTitle(),
            'settings' => $widget->getSettings(),
        ];
    }

    public function deleteAction(Request $request, $id)
    {
        $widget = $this->findOr404($id);

        $this->denyAccessUnlessGranted('delete', $widget);

        $this->entityManager->remove($widget);
        $this->entityManager->flush();
    }

    public function updateAction(Request $request, $id)
    {
        $entity = $this->findOr404($id);

        $this->denyAccessUnlessGranted('edit', $entity);

        $form = $this->createForm(WidgetType::class, $entity, [
            'method' => 'PATCH',
        ]);

        $this->formHandleRequest($form, $request);
        if($form->isValid()) {
            $this->entityManager->flush();
            return new JsonResponse();
        }

        $errors = $this->get('serializer')->normalize($form->getErrors(true));
        throw $this->createBadRequestException($errors);

    }

    public function createAction(Request $request)
    {
        $widget = null;
        if($request->get('type')) {
            $widget = $this->widgetFactory->create($request->get('type'));
        }

        $form = $this->createForm(WidgetType::class, $widget);

        //$this->formHandleRequest($form, $request);

        $form->submit($request->request->all(), false);

        if($form->isValid()) {

            $entity = $form->getData();

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return new JsonResponse($entity->getId());
        }
        $errors = $this->get('serializer')->normalize($form->getErrors(true));
        throw $this->createBadRequestException($errors);
    }
}