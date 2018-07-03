<?php
namespace SalesBundle\Entity;

use ShippingBundle\Entity\Carrier;

class ShipmentTrack
{
    const STATE_NEW = 'new';
    const STATE_PROCESSING = 'processing';
    const STATE_COMPLETED = 'completed';

    /**
     * @var int
     */
    private $id;

    /**
     * @var Carrier
     */
    private $carrier;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $trackingNumber;

    /**
     * @var Shipment
     */
    private $shipment;

    /**
     * @var string
     */
    private $state = self::STATE_NEW;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return ShipmentTrack
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return Carrier
     */
    public function getCarrier()
    {
        return $this->carrier;
    }

    /**
     * @param Carrier $carrier
     * @return ShipmentTrack
     */
    public function setCarrier($carrier)
    {
        $this->carrier = $carrier;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return ShipmentTrack
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTrackingNumber()
    {
        return $this->trackingNumber;
    }

    /**
     * @param string $trackingNumber
     * @return ShipmentTrack
     */
    public function setTrackingNumber($trackingNumber)
    {
        $this->trackingNumber = $trackingNumber;
        return $this;
    }

    /**
     * @return Shipment
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * @param Shipment $shipment
     * @return ShipmentTrack
     */
    public function setShipment($shipment)
    {
        $this->shipment = $shipment;
        return $this;
    }


}