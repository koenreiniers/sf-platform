<?php
namespace ShippingBundle\Carrier\Adapter;

use SalesBundle\Entity\ShipmentTrack;
use ShippingBundle\Carrier\CarrierAdapterInterface;

class CustomAdapter implements CarrierAdapterInterface
{
    public function requestTrackingNumber(ShipmentTrack $shipmentTrack)
    {
        return 'custom_tracking_number';
    }

    public function getTrackingUrl($trackingNumber)
    {
        return 'custom_tracking_url';
    }

    public function getCode()
    {
        return 'custom';
    }
}