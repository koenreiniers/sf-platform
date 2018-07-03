<?php
namespace Raw\Bundle\PlatformBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\VarDumper\VarDumper;

abstract class AdvancedController extends Controller
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @return string|null
     */
    protected function getClassName()
    {
        return null;
    }

    protected function createForm($type, $data = null, array $options = array())
    {
        if($this->isApiRequest()) {
            $options['csrf_protection'] = false;
            $options['allow_extra_fields'] = true;
        }
        return parent::createForm($type, $data, $options);
    }

    protected function formHandleRequest(FormInterface $form, Request $request)
    {
        $submittedData = $request->get($form->getName());
        $clearMissing = true;
        if($this->isApiRequest()) {
            $submittedData = $request->request->all();
        }
        if($request->isMethod('PATCH')) {
            $clearMissing = false;
        }
        $form->submit($submittedData, $clearMissing);
    }

    /**
     * @param null|string $message
     * @param \Exception|null $previous
     * @param int $code
     * @return BadRequestHttpException
     */
    protected function createBadRequestException($message = null, \Exception $previous = null, $code = 0)
    {
        if(is_array($message)) {
            $message = json_encode($message);
        }
        return new BadRequestHttpException($message, $previous, $code);
    }

    /**
     * @return bool
     */
    protected function isApiRequest()
    {
        return $this->get('request_stack')->getCurrentRequest()->attributes->get('_api') !== null;
    }

    /**
     * @param string $alias
     * @param null $indexBy
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function createQueryBuilder($alias, $indexBy = null)
    {
        return $this->repository->createQueryBuilder($alias, $indexBy);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
        if($this->getClassName() !== null) {
            $this->repository = $this->entityManager->getRepository($this->getClassName());
        }
    }

    protected function findOr404($id)
    {
        $entity = $this->repository->find($id);
        if($entity === null) {
            throw $this->createNotFoundException();
        }
        return $entity;
    }
}