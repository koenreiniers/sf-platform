<?php
namespace Raw\Bundle\BatchBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Raw\Bundle\BatchBundle\Entity\JobInstance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Template
 */
class JobInstanceController extends Controller
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $repository;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
        $this->repository = $this->entityManager->getRepository(JobInstance::class);
    }

    protected function findOr404($id)
    {
        $entity = $this->repository->find($id);
        if($entity === null) {
            throw $this->createNotFoundException();
        }
        return $entity;
    }

    public function launchAction(Request $request, $id)
    {
        $jobInstance = $this->findOr404($id);

        $launcher = $this->get('raw_batch.job_launcher');
        $jobExecution = $launcher->launch($jobInstance, [], true);
        $this->addFlash('success', 'Job launched');
        return $this->redirectToRoute('raw_batch.job_execution.view', ['id' => $jobExecution->getId()]);
    }
}