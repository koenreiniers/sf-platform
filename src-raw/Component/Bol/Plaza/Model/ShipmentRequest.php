<?php
namespace Raw\Component\Bol\Plaza\Model;

class ShipmentRequest
{
    /**
     * @var int
     */
    protected $orderItemId;

    /**
     * @var string
     */
    protected $shipmentReference;

    /**
     * @var \DateTime
     */
    protected $dateTime;

    /**
     * @var \DateTime
     */
    protected $expectedDeliveryDate;

    /**
     * @var Transport
     */
    protected $transport;

    public static function create()
    {
        return new self();
    }

    /**
     * @return int
     */
    public function getOrderItemId()
    {
        return $this->orderItemId;
    }

    /**
     * @param int $orderItemId
     * @return ShipmentRequest
     */
    public function setOrderItemId($orderItemId)
    {
        $this->orderItemId = $orderItemId;
        return $this;
    }

    /**
     * @return string
     */
    public function getShipmentReference()
    {
        return $this->shipmentReference;
    }

    /**
     * @param string $shipmentReference
     * @return ShipmentRequest
     */
    public function setShipmentReference($shipmentReference)
    {
        $this->shipmentReference = $shipmentReference;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     * @return ShipmentRequest
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpectedDeliveryDate()
    {
        return $this->expectedDeliveryDate;
    }

    /**
     * @param \DateTime $expectedDeliveryDate
     * @return ShipmentRequest
     */
    public function setExpectedDeliveryDate($expectedDeliveryDate)
    {
        $this->expectedDeliveryDate = $expectedDeliveryDate;
        return $this;
    }

    /**
     * @return Transport
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @param Transport $transport
     * @return ShipmentRequest
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;
        return $this;
    }
}