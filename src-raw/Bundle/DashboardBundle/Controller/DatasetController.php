<?php
namespace Raw\Bundle\DashboardBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Raw\Bundle\DashboardBundle\Entity\Widget;
use Raw\Bundle\DashboardBundle\Form\Type\WidgetType;
use Raw\Bundle\PlatformBundle\Controller\AdvancedController;
use Raw\Bundle\UserBundle\Entity\User;
use Raw\Component\Statistics\Dataset\Dataset;
use Raw\Component\Statistics\Loader\ChainLoader;
use Raw\Component\Statistics\Metric\Metric;
use Raw\Component\Statistics\Statistic;
use Raw\Component\Statistics\StatisticCollection;
use Raw\Component\Statistics\StatisticManager;
use Raw\Component\Widget\WidgetFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class DatasetController extends AdvancedController
{
    /**
     * @var StatisticManager
     */
    private $datasetManager;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->datasetManager = $container->get('raw_dashboard.dataset.manager');
        parent::setContainer($container);
    }

    public function indexAction(Request $request)
    {
        /** @var Dataset[] $statistics */
        $statistics = $this->datasetManager->getStatisticCollection();

        $data = [];

        foreach($statistics as $name => $statistic) {
            /** @var Statistic $statistic */
            $data[$name] = [
                'label' => $statistic->getLabel(),
            ];
        }

        return new JsonResponse($data);
    }

    public function viewAction(Request $request, $name)
    {
        /** @var Dataset $dataset */
        $dataset = $this->datasetManager->getStatistic($name);

        $parameters = $request->get('parameters', []);

        $data = $dataset->getDataPoints($parameters);

        return new JsonResponse($data);
    }
}