<?php
namespace SalesBundle\Statistics;

use Doctrine\ORM\EntityManager;
use Raw\Component\Statistics\CallbackStatistic;
use Raw\Component\Statistics\Dataset\CallbackDataset;
use Raw\Component\Statistics\Dataset\Dataset;
use Raw\Component\Statistics\Dataset\DoctrineDataset;
use Raw\Component\Statistics\StatisticCollection;
use Raw\Component\Statistics\StatisticLoaderInterface;
use SalesBundle\Entity\Order;
use SalesBundle\Entity\Shipment;
use Symfony\Component\VarDumper\VarDumper;

class OrderStatisticLoader implements StatisticLoaderInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * OrderStatisticLoader constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getOrders($state, \DateTime $start, \DateTime $end)
    {




        $qb = $this->entityManager->createQueryBuilder()
            ->from(Order::class, 'o')
            ->select('COUNT(o)')
            ->where('o.state = :state')
            ->andWhere('o.orderDate >= :start')
            ->andWhere('o.orderDate <= :end')
            ->setParameters([
                'state' => $state,
                'start' => $start,
                'end' => $end,
            ])
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    private function resolveCriteria(array $criteria)
    {
        $start = $criteria['start'];
        $end = $criteria['end'];
        if(!$start instanceof \DateTime) {
            $start = new \DateTime($start);
        }
        if(!$end instanceof \DateTime) {
            $end = new \DateTime($end);
        }
        return [
            'start' => $start,
            'end' => $end,
        ];
    }

    public function getCompletedShipments(\DateTime $start, \DateTime $end)
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->from(Shipment::class, 'shipment')
            ->select('COUNT(shipment)')
            ->andWhere('shipment.createdAt >= :start')
            ->andWhere('shipment.createdAt <= :end')
            ->setParameters([
                'start' => $start,
                'end' => $end,
            ])
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getRevenuePerDay(array $criteria)
    {

        $qb = $this->entityManager->createQueryBuilder()
            ->from(Order::class, 'o')
            ->select('SUM(o.grandTotal) AS revenue, DATE_FORMAT(o.orderDate, :dateFormat) AS date')
            ->setParameter('dateFormat', '%d-%m-%Y')
            ->groupBy('date');
        $results = $qb->getQuery()->getArrayResult();
        return $results;
    }

    public function getCompletedOrders(array $criteria)
    {
        $criteria = $this->resolveCriteria($criteria);
        return $this->getOrders(Order::STATE_COMPLETED, $criteria['start'], $criteria['end']);
    }

    public function getProcessingOrders(array $criteria)
    {
        $criteria = $this->resolveCriteria($criteria);
        return $this->getOrders(Order::STATE_PROCESSING, $criteria['start'], $criteria['end']);
    }


    public function getOpenOrders(array $criteria)
    {
        $criteria = $this->resolveCriteria($criteria);
    }

    public function load(StatisticCollection $statistics)
    {


        $statistics->add('open_orders', new CallbackStatistic([$this, 'getProcessingOrders']));
        $statistics->add('completed_orders', new CallbackStatistic([$this, 'getCompletedOrders']));
        $statistics->add('completed_shipments', new CallbackStatistic(function(array $criteria){
            $criteria = $this->resolveCriteria($criteria);
            return $this->getCompletedShipments($criteria['start'], $criteria['end']);
        }));

    }
}