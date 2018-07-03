<?php
namespace SalesBundle\Statistics;

use CrmBundle\Entity\Customer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Currency\CurrencyConverter;
use SalesBundle\Entity\Order;
use SalesBundle\Entity\OrderItem;
use SalesBundle\Entity\Shipment;
use Symfony\Component\VarDumper\VarDumper;

class OrderStatistics
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var CurrencyConverter
     */
    private $currencyConverter;

    /**
     * OrderStatistics constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager, CurrencyConverter $currencyConverter)
    {
        $this->entityManager = $entityManager;
        $this->currencyConverter = $currencyConverter;
    }

    private function applyCriteria(QueryBuilder $qb, array $criteria, array $filters)
    {
        foreach($criteria as $filterName => $filterValue) {
            if(!isset($filters[$filterName])) {
                throw new \Exception(sprintf('Invalid criterium "%s"', $filterName));
            }
            $filters[$filterName]($qb, $filterValue);
        }
    }

    public function getTotalRevenue(array $criteria = [])
    {
        $qb = $this->entityManager->getRepository(Order::class)->createQueryBuilder('o');

        $qb
            ->select('SUM(o.grandTotal) AS revenue, store.currencyCode')
            ->join('o.store', 'store')
            ->groupBy('store.currencyCode')
        ;

        $filters = [
            'after' => function(QueryBuilder $qb, $value) {
                $qb
                    ->andWhere('o.orderDate >= :after')
                    ->setParameter('after', $value);
            },
            'before' => function(QueryBuilder $qb, $value) {
                $qb
                    ->andWhere('o.orderDate <= :before')
                    ->setParameter('before', $value);
            },
        ];
        $this->applyCriteria($qb, $criteria, $filters);

        $results = $qb->getQuery()->getResult();

        $total = 0;
        foreach($results as $result) {
            $currencyCode = $result['currencyCode'];
            $currencyTotal = $this->currencyConverter->convertCurrency($result['revenue'], $currencyCode);
            $total += $currencyTotal;
        }

        return $total;
    }

    public function getAmountOfShipmentsSince(\DateTime $date)
    {
        $qb = $this->entityManager->getRepository(Shipment::class)->createQueryBuilder('shipment');

        $qb
            ->select('COUNT(shipment)')
            ->where('shipment.createdAt >= :date')
            ->setParameter('date', $date)
            ;
        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    public function getAmountOfNewCustomersSince(\DateTime $date)
    {
        $qb = $this->entityManager->getRepository(Customer::class)->createQueryBuilder('customer');

        $qb
            ->select('COUNT(customer)')
            ->where('customer.externalCreatedAt >= :date')
            ->setParameter('date', $date);

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return array
     */
    public function getOrderAmountPerState(array $criteria = [])
    {
        $filters = [
            'channel' => function(QueryBuilder $qb, $value) {
                $qb
                    ->join('o.store', 'store')
                    ->andWhere('store.channel = :channel')
                    ->setParameter('channel', $value);
            },
            'start' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('o.orderDate >= :start')
                    ->setParameter('start', $value);
            },
            'end' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('o.orderDate <= :end')
                    ->setParameter('end', $value);
            },
        ];

        $qb = $this->entityManager->getRepository(Order::class)->createQueryBuilder('o');

        $qb->select('COUNT(o) AS amount, o.state')
            ->groupBy('o.state');

        $this->applyCriteria($qb, $criteria, $filters);

        $amountPerState = [
            'new' => 0,
            'processing' => 0,
            'cancelled' => 0,
            'on_hold' => 0,
            'closed' => 0,
            'completed' => 0,
        ];
        foreach($qb->getQuery()->getResult() as $result) {
            $amountPerState[$result['state']] = $result['amount'];
        }
        return $amountPerState;
    }

    public function getRecentlyBoughtItems(array $criteria = [], $limit = null)
    {
        $filters = [
            'customer' => function(QueryBuilder $qb, $value) {
                $qb->join('orderItem.order', 'o');
                $qb->andWhere('o.customer = :customer');
                $qb->setParameter('customer', $value);
            },
        ];

        $qb = $this->entityManager->createQueryBuilder()
            ->from(OrderItem::class, 'orderItem')
            ->select('orderItem.sku, orderItem.name, orderItem.qtyOrdered AS qty, o.orderDate')
            ->orderBy('o.orderDate', 'DESC')
        ;

        $this->applyCriteria($qb, $criteria, $filters);

        if($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function getMostBoughtItems(array $criteria = [], $limit = null)
    {
        $filters = [
            'customer' => function(QueryBuilder $qb, $value) {
                $qb->join('orderItem.order', 'o');
                $qb->andWhere('o.customer = :customer');
                $qb->setParameter('customer', $value);
            },
        ];

        $qb = $this->entityManager->createQueryBuilder()
            ->from(OrderItem::class, 'orderItem')
            ->select('orderItem.sku, orderItem.name, SUM(orderItem.qtyOrdered) AS qty')
            ->groupBy('orderItem.sku')
            ->orderBy('qty', 'DESC')
        ;

        $this->applyCriteria($qb, $criteria, $filters);

        if($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
}