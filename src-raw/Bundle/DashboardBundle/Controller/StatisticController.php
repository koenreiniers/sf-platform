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

class StatisticController extends AdvancedController
{
    /**
     * @var StatisticManager
     */
    private $statisticManager;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->statisticManager = $container->get('raw_dashboard.statistics.manager');
        parent::setContainer($container);
    }

    public function indexAction(Request $request)
    {
        $statistics = $this->statisticManager->getStatisticCollection();

        $data = [];

        foreach($statistics as $name => $statistic) {
            /** @var Statistic $statistic */
            $data[$name] = [
                'label' => $statistic->getLabel(),
            ];
        }

        return new JsonResponse($data);
    }

    public function metricAction(Request $request, $name)
    {
        $metric = new Metric(function(\DatePeriod $period){
            $qb = $this->entityManager->createQueryBuilder();
            $qb
                ->from(User::class, 'user')
                ->select('COUNT(user)')
                ->andWhere('user.createdAt >= :start')
                ->andWhere('user.createdAt <= :end')
                ->setParameters([
                    'start' => $period->getStartDate(),
                    'end' => $period->getEndDate(),
                ]);
            return (int)$qb->getQuery()->getSingleScalarResult();
        });

        $end = new \DateTime();
        $start = clone $end;
        $start = $start->sub(new \DateInterval('P1M'));
        if($request->get('start') !== null) {
            $start = new \DateTime($request->get('start'));
        }
        if($request->get('end') !== null) {
            $end = new \DateTime($request->get('end'));
        }

//        $data = $metric->getData($start, $end);

        if(!$this->statisticManager->hasStatistic($name)) {
            throw $this->createNotFoundException();
        }

        $statistic = $this->statisticManager->getStatistic($name);

        $interval = $start->diff($end);
        $previousStart = clone $start;
        $previousStart = $previousStart->sub($interval);


        $previousData = $statistic->getData(['start' => $previousStart, 'end' => $start]);

        $currentData = $statistic->getData(['start' => $start, 'end' => $end]);
        $data = [
            'previousData' => $previousData,
            'currentData' => $currentData,
        ];


        $diff = $currentData - $previousData;
        $progress = $currentData == 0 ? 0 : 100;
        if($previousData != 0) {
            $progress = round($currentData / $previousData * 100, 2) - 100;
        }

        $data['diff'] = $diff;
        $data['progress'] = $progress;



        return new JsonResponse($data);
    }

    public function viewAction(Request $request, $name)
    {
        if(!$this->statisticManager->hasStatistic($name)) {
            throw $this->createNotFoundException();
        }

        $statistic = $this->statisticManager->getStatistic($name);

        $parameters = $request->get('parameters', []);

        $data = $statistic->getData($parameters);

        return new JsonResponse($data);
    }
}