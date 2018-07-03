<?php
namespace Raw\Bundle\DashboardBundle\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Raw\Bundle\DashboardBundle\Entity\Dashboard;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class DashboardExtension extends \Twig_Extension
{
    use ContainerAwareTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $repository;

    private function init()
    {
        $this->entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $this->repository = $this->entityManager->getRepository(Dashboard::class);
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

    protected function getUser()
    {
        return $this->container->get('security.token_storage')->getToken()->getUser();
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
        $this->entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $this->repository = $this->entityManager->getRepository(Dashboard::class);
        return $this->repository->find($id);
        return parent::findOr404($id);
    }

    public function renderDashboard(\Twig_Environment $environment, $id = null)
    {
        $this->init();
        $dashboard = $this->findOr404($id);
        return $environment->render('RawDashboardBundle:Dashboard:dashboard.html.twig', [
            'dashboard' => $dashboard,
        ]);
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('raw_dashboard', [$this, 'renderDashboard'], ['is_safe' => ['html'],'needs_environment' => true])
        ];
    }
}