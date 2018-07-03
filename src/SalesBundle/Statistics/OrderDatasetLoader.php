<?php
namespace SalesBundle\Statistics;

use Doctrine\ORM\EntityManager;
use Raw\Component\Statistics\Dataset\CallbackDataset;
use Raw\Component\Statistics\Dataset\Dataset;
use Raw\Component\Statistics\Dataset\DoctrineDataset;
use Raw\Component\Statistics\StatisticCollection;
use Raw\Component\Statistics\StatisticLoaderInterface;
use SalesBundle\Entity\Order;
use Symfony\Component\VarDumper\VarDumper;

class OrderDatasetLoader implements StatisticLoaderInterface
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

    public function getRevenuePerDay(array $criteria)
    {
        $criteria = $this->resolveCriteria($criteria);
        /** @var \DateTime $start **/
        $start = $criteria['start'];
        /** @var \DateTime $end */
        $end = $criteria['end'];

        $diff = $start->diff($end);


        $qb = $this->entityManager->createQueryBuilder()
            ->from(Order::class, 'o')
            ->select('SUM(o.grandTotal) AS revenue, DATE_FORMAT(o.orderDate, :dateFormat) AS date')
            ->andWhere('o.orderDate >= :start')
            ->andWhere('o.orderDate <= :end')
            ->setParameters([
                'dateFormat' => '%d-%m-%Y',
                'start' => $start,
                'end' => $end,
            ])
            ->groupBy('date');
        $results = $qb->getQuery()->getArrayResult();
        $res = [];
        foreach($results as $result) {
            $res[$result['date']] = $result['revenue'];
        }

//        $current = clone $start;
//        do {
//
//            $date = $current->format('d-m-Y');
//
//            if(!isset($res[$date])) {
//                $res[$date] = 0;
//            }
//
//            $current = $current->add(new \DateInterval('P1D'));
//
//        } while($current < $end);

        return $res;
    }

    public function load(StatisticCollection $statistics)
    {
        $datasets = $statistics;

        $datasets->add('revenue_per_day', new CallbackDataset(function(array $criteria){
            return $this->getRevenuePerDay($criteria);
        }));
        $datasets->add('orders_by_status', new CallbackDataset(function(array $criteria){
            global $kernel;
            if(isset($criteria['start']) && !$criteria['start'] instanceof \DateTime) {
                $criteria['start'] = new \DateTime($criteria['start']);
            }
            if(isset($criteria['end']) && !$criteria['end'] instanceof \DateTime) {
                $criteria['end'] = new \DateTime($criteria['end']);
            }
            $stats = $kernel->getContainer()->get('sales.statistics.order');
            return $stats->getOrderAmountPerState($criteria);
        }));
    }
}