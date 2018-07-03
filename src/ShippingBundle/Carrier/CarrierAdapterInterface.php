<?php
namespace ShippingBundle\Carrier;

use SalesBundle\Entity\ShipmentTrack;

interface CarrierAdapterInterface
{
    public function requestTrackingNumber(ShipmentTrack $shipmentTrack);
}