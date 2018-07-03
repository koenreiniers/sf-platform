<?php
namespace ShippingBundle\Carrier\Adapter;

use SalesBundle\Entity\ShipmentTrack;
use ShippingBundle\Carrier\CarrierAdapterInterface;

class PostnlAdapter implements CarrierAdapterInterface
{
    public function requestTrackingNumber(ShipmentTrack $shipmentTrack)
    {
        return 'tracking_number';
    }

    public function getTrackingUrl($trackingNumber)
    {
        return 'tracking_url';
    }

    public function getCode()
    {
        return 'postnl';
    }
}