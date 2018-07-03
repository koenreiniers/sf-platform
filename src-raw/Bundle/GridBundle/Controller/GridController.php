<?php
namespace Raw\Bundle\GridBundle\Controller;


use Doctrine\ORM\EntityManager;
use Raw\Component\Grid\GridFactory;
use Raw\Component\Grid\DataSource\Doctrine\QueryBuilderLoader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\VarDumper\VarDumper;

class GridController extends Controller
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var GridFactory
     */
    private $gridFactory;



    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
        $this->gridFactory = $container->get('raw_grid.factory');
    }

    public function viewAction(Request $request, $gridName)
    {
        $grid = $this->gridFactory->getGrid($gridName);

        return new JsonResponse($grid->createView()->vars);
    }

    public function updateAction(Request $request, $gridName)
    {
        $grid = $this->gridFactory->getGrid($gridName);
        $data = $request->get('data');

        $idProperty = $grid->getProperty('identifier');

        $id = $data[$idProperty];
        unset($data[$idProperty]);

        $accessor = PropertyAccess::createPropertyAccessor();

        $qb = $this->get('doctrine.orm.default_entity_manager')->createQueryBuilder();
        (new QueryBuilderLoader($qb))->load($grid->getParameter('data_source.options')['query']);

        $part = $qb->getDQLPart('from');
    }

    public function massAction(Request $request, $gridName)
    {

    }

    public function dataAction(Request $request, $gridName)
    {
        $grid = $this->gridFactory->getGrid($gridName);

        $grid->handleRequest($request);

        $data = $grid->getData();

        $items = $data->toArray();

        $totalCount = $data->getTotalSize();

        $response = new JsonResponse($items);

        $response->headers->set('X-Total-Count', $totalCount);
        return $response;
    }

}