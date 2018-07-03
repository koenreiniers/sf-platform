<?php
namespace SalesBundle\Repository;

use AppBundle\Entity\Channel;
use Doctrine\ORM\EntityRepository;
use SalesBundle\Entity\Shipment;

class ShipmentRepository extends EntityRepository
{
    /**
     * @param Channel $channel
     * @return Shipment[]
     */
    public function findShipmentsToExport(Channel $channel)
    {
        $qb = $this->createQueryBuilder('shipment')
            ->select('shipment')
            #->where('shipment.externalId IS NULL')
            ->where('shipment.state = :state')
            ->join('shipment.order', 'order')
            ->join('order.store', 'store')
            ->andWhere('store.channel = :channel')
            ->setParameter('channel', $channel)
            ->setParameter('state', Shipment::STATE_PROCESSING)
        ;
        return $qb->getQuery()->getResult();
    }
}