<?php
namespace SalesBundle\Entity;

use AppBundle\Entity\Store;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Shipment
{
    const STATE_NEW = 'new'; // Created, requesting tracking numbers
    const STATE_PROCESSING = 'processing'; // Waiting to be exported to channel
    const STATE_COMPLETED = 'completed'; // Completed, exported to channel

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $externalId;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var ShipmentItem[]|Collection
     */
    private $shipmentItems;

    /**
     * @var ShipmentTrack[]|Collection
     */
    private $shipmentTracks;

    /**
     * @var string
     */
    private $state = self::STATE_NEW;

    /**
     * @var \DateTime
     */
    private $createdAt;

    public function __construct()
    {
        $this->shipmentItems = new ArrayCollection();
        $this->shipmentTracks = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Shipment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
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
     * @return Shipment
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
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
     * @return Shipment
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     * @return Shipment
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }



    /**
     * @return ArrayCollection|Collection|ShipmentItem[]
     */
    public function getShipmentItems()
    {
        return $this->shipmentItems;
    }

    public function addShipmentItem(ShipmentItem $item)
    {
        $this->shipmentItems[] = $item;
        return $this;
    }

    public function removeShipmentItem(ShipmentItem $item)
    {
        $this->shipmentItems->removeElement($item);
        return $this;
    }

    /**
     * @return Collection|ShipmentTrack[]
     */
    public function getShipmentTracks()
    {
        return $this->shipmentTracks;
    }



    public function addShipmentTrack(ShipmentTrack $shipmentTrack)
    {
        $this->shipmentTracks[] = $shipmentTrack;
        return $this;
    }

    public function removeShipmentTrack(ShipmentTrack $shipmentTrack)
    {
        $this->shipmentTracks->removeElement($shipmentTrack);
        return $this;
    }
}