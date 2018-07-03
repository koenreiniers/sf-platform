<?php
namespace Raw\Bundle\DashboardBundle\Controller;

use Raw\Bundle\DashboardBundle\Entity\Widget;
use Raw\Bundle\DashboardBundle\Form\Type\DashboardType;
use Raw\Bundle\DashboardBundle\Entity\Dashboard;
use Raw\Bundle\DashboardBundle\Normalizer\FormNormalizer;
use Raw\Bundle\DashboardBundle\Repository\DashboardRepository;
use Raw\Bundle\PlatformBundle\Controller\AdvancedController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\VarDumper\VarDumper;

class DashboardController extends AdvancedController
{
    /**
     * @var DashboardRepository
     */
    protected $repository;

    protected function getClassName()
    {
        return Dashboard::class;
    }

    private function findOrCreateDefaultDashboard()
    {
        $dashboard = $this->repository->findDefaultDashboardByOwner($this->getUser());
        if($dashboard === null) {
            $dashboard = $this->createDashboard();
            $dashboard->setDefault(true);
            $dashboard->setOwner($this->getUser());
            $dashboard->setName('Your first dashboard');
            $this->entityManager->persist($dashboard);
            $this->entityManager->flush();
        }
        return $dashboard;
    }

    private function createDashboard()
    {
        $dashboard = new Dashboard();
        return $dashboard;
    }

    protected function findOr404($id)
    {
        if($id === null) {
            return $this->findOrCreateDefaultDashboard();
        }
        return parent::findOr404($id);
    }

    private function acljo()
    {
        $dashboard = null;
        $aclProvider = $this->get('security.acl.provider');

        $objectIdentity = ObjectIdentity::fromDomainObject($dashboard);

        $acl = $aclProvider->createAcl($objectIdentity);

        $user = $this->getUser();
        $tokenStorage = $this->get('security.token_storage');

        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);

        $aclProvider->updateAcl($acl);
    }

    public function viewAction(Request $request, $id = null)
    {
        $dashboard = $this->findOr404($id);

        $this->denyAccessUnlessGranted('view', $dashboard);

        if($this->isApiRequest()) {
            return $this->get('serializer')->normalize($dashboard);
        }

        return $this->render('RawDashboardBundle:Dashboard:view.html.twig', [
            'dashboard' => $dashboard,
        ]);
    }

    public function updateAction(Request $request, $id)
    {
        $dashboard = $this->findOr404($id);

        $this->denyAccessUnlessGranted('edit', $dashboard);


        $form = $this->createForm(DashboardType::class, $dashboard, [
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



    public function indexAction(Request $request)
    {
        $qb = $this->createQueryBuilder('dashboard')
            ->where('dashboard.owner = :owner')
            ->setParameter('owner', $this->getUser());

        return $qb->getQuery()->getArrayResult();
    }

    public function createAction(Request $request)
    {
        $form = $this->createForm(DashboardType::class);

        $this->formHandleRequest($form, $request);

        if($form->isValid()) {

            $entity = $form->getData();

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return new JsonResponse($entity->getId());
        }
        $errors = $this->get('serializer')->normalize($form->getErrors(true));
        throw $this->createBadRequestException($errors);
    }

    public function widgetsAction(Request $request, $id)
    {
        /** @var Dashboard $dashboard */
        $dashboard = $this->findOr404($id);

        $this->denyAccessUnlessGranted('view', $dashboard);

        $qb = $this->entityManager->createQueryBuilder()
            ->from(Widget::class, 'widget')
            ->select('widget')
            ->where('widget.dashboard = :dashboard')
            ->setParameter('dashboard', $dashboard);

        $widgets = $qb->getQuery()->getArrayResult();

        return $widgets;
    }
}