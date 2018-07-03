<?php
namespace SalesBundle\Entity;

use AppBundle\Entity\Store;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ShipmentItem
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $externalId;

    /**
     * @var Shipment
     */
    private $shipment;

    /**
     * @var OrderItem
     */
    private $orderItem;

    /**
     * @var float
     */
    private $qty;

    public function __construct()
    {
        $this->shipmentItems = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getShipmentItems()
    {
        return $this->shipmentItems;
    }

    /**
     * @param ArrayCollection $shipmentItems
     * @return ShipmentItem
     */
    public function setShipmentItems($shipmentItems)
    {
        $this->shipmentItems = $shipmentItems;
        return $this;
    }

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
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     * @return ShipmentItem
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * @return float
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * @param float $qty
     * @return ShipmentItem
     */
    public function setQty($qty)
    {
        $this->qty = $qty;
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
     * @return ShipmentItem
     */
    public function setShipment(Shipment $shipment)
    {
        $this->shipment = $shipment;
        return $this;
    }

    /**
     * @return OrderItem
     */
    public function getOrderItem()
    {
        return $this->orderItem;
    }

    /**
     * @param OrderItem $orderItem
     * @return ShipmentItem
     */
    public function setOrderItem(OrderItem $orderItem)
    {
        $this->orderItem = $orderItem;
        return $this;
    }
}