<?php
namespace Raw\Bundle\ShippingBundle\Batch\Reader;

use Doctrine\ORM\EntityManager;
use Raw\Component\Batch\Item\ItemReaderInterface;
use Raw\Component\Batch\Item\Reader\BasicItemReader;
use SalesBundle\Entity\ShipmentTrack;

class UnprocessedShipmentTracksReader extends BasicItemReader
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UnprocessedShipmentTracksReader constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function loadItems()
    {
        return $this->entityManager->getRepository(ShipmentTrack::class)->findBy([
            'state' => ShipmentTrack::STATE_NEW,
        ]);
    }
}