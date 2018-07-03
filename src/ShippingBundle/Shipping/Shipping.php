<?php
namespace ShippingBundle\Shipping;

use Doctrine\ORM\EntityManager;
use SalesBundle\Entity\Shipment;
use SalesBundle\Entity\ShipmentTrack;
use ShippingBundle\Carrier\CarrierRegistry;

class Shipping
{
    /**
     * @var CarrierRegistry
     */
    private $carrierRegistry;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Shipping constructor.
     * @param CarrierRegistry $carrierRegistry
     * @param EntityManager $entityManager
     */
    public function __construct(CarrierRegistry $carrierRegistry, EntityManager $entityManager)
    {
        $this->carrierRegistry = $carrierRegistry;
        $this->entityManager = $entityManager;
    }

    public function getUnprocessedShipmentTracks()
    {
        /** @var ShipmentTrack[] $shipmentTracks */
        $shipmentTracks = $this->entityManager->getRepository(ShipmentTrack::class)->findBy([
            'state' => ShipmentTrack::STATE_NEW,
        ]);
        return $shipmentTracks;
    }


    public function processShipmentTracks()
    {
        /** @var ShipmentTrack[] $shipmentTracks */
        $shipmentTracks = $this->entityManager->getRepository(ShipmentTrack::class)->findBy([
            'state' => ShipmentTrack::STATE_NEW,
        ]);

        foreach($shipmentTracks as $shipmentTrack) {
            $adapter = $this->carrierRegistry->getAdapter($shipmentTrack->getCarrier()->getCode());

            try {
                $trackingNumber = $adapter->requestTrackingNumber($shipmentTrack);
                $shipmentTrack->setTrackingNumber($trackingNumber);
                $shipmentTrack->setState(ShipmentTrack::STATE_PROCESSING);
            } catch(\Exception $e) {
                // TODO
            }
        }

        $this->entityManager->flush();

        $shipments = $this->entityManager->getRepository(Shipment::class)->findBy([
            'state' => Shipment::STATE_NEW
        ]);

        foreach($shipments as $shipment) {
            $success = true;
            foreach($shipment->getShipmentTracks() as $shipmentTrack) {
                if($shipmentTrack->getState() !== ShipmentTrack::STATE_PROCESSING) {
                    $success = false;
                    break;
                }
            }
            if($success) {
                $shipment->setState(Shipment::STATE_PROCESSING);
            }
        }

        $this->entityManager->flush();

        return $shipmentTracks;
    }
}