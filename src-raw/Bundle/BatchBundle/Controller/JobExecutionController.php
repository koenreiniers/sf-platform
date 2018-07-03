<?php
namespace Raw\Bundle\BatchBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Raw\Bundle\BatchBundle\Entity\JobExecution;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Template
 */
class JobExecutionController extends Controller
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
        $this->repository = $this->entityManager->getRepository(JobExecution::class);
    }

    protected function findOr404($id)
    {
        $entity = $this->repository->find($id);
        if($entity === null) {
            throw $this->createNotFoundException();
        }
        return $entity;
    }


    /**
     * @param JobExecution $data
     * @return array
     */
    private function normalize($data)
    {
        $jobExecution = $data;

        $stepExecutions = [];

        foreach($jobExecution->getStepExecutions() as $stepExecution) {

            $warnings = [];
            foreach($stepExecution->getWarnings() as $warning) {
                $warnings[] = [
                    'id' => $warning->getId(),
                    'message' => $warning->getMessage(),
                    'item' => $warning->getItem(),
                ];
            }

            $stepExecutions[] = [
                'id' => $stepExecution->getId(),
                'status' => $stepExecution->getStatus(),
                'summary' => $stepExecution->getSummary(),
                'stepName' => $stepExecution->getStepName(),
                'startedAt' => $stepExecution->getStartedAt() ? $stepExecution->getStartedAt()->format(\DATE_ATOM) : null,
                'completedAt' => $stepExecution->getCompletedAt() ? $stepExecution->getCompletedAt()->format(\DATE_ATOM) : null,
                'warnings' => $warnings,
            ];

        }

        return [
            'id' => $jobExecution->getId(),
            'status' => $jobExecution->getStatus(),
            'stepExecutions' => $stepExecutions,
        ];
    }

    public function logAction(Request $request, $id)
    {
        $jobExecution = $this->findOr404($id);

        $logPath = $jobExecution->getLogPath();

        if(file_exists($logPath)) {
            echo file_get_contents($logPath);
        }
        echo 'Log not found';die;
    }

    public function viewAction(Request $request, $id)
    {

        $jobExecution = $this->findOr404($id);

        if($request->getContentType() === 'json') {
            $data = $this->normalize($jobExecution);
            return new JsonResponse($data);
        }

        return [
            'jobExecution' => $jobExecution,
        ];
    }

}