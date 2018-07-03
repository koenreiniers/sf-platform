<?php
namespace Raw\Component\Bol\Plaza\Model;

class Order
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $dateTimeCustomer;

    /**
     * @var \DateTime
     */
    protected $dateTimeDropShipper;

    /**
     * @var AddressDetails
     */
    protected $shipmentDetails;

    /**
     * @var AddressDetails
     */
    protected $billingDetails;

    /**
     * @var OrderItem[]
     */
    protected $items;

    public function __construct()
    {
        $this->items = [];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Order
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateTimeCustomer()
    {
        return $this->dateTimeCustomer;
    }

    /**
     * @param \DateTime $dateTimeCustomer
     * @return Order
     */
    public function setDateTimeCustomer($dateTimeCustomer)
    {
        $this->dateTimeCustomer = $dateTimeCustomer;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateTimeDropShipper()
    {
        return $this->dateTimeDropShipper;
    }

    /**
     * @param \DateTime $dateTimeDropShipper
     * @return Order
     */
    public function setDateTimeDropShipper($dateTimeDropShipper)
    {
        $this->dateTimeDropShipper = $dateTimeDropShipper;
        return $this;
    }

    /**
     * @return AddressDetails
     */
    public function getShipmentDetails()
    {
        return $this->shipmentDetails;
    }

    /**
     * @param AddressDetails $shipmentDetails
     * @return Order
     */
    public function setShipmentDetails($shipmentDetails)
    {
        $this->shipmentDetails = $shipmentDetails;
        return $this;
    }

    /**
     * @return AddressDetails
     */
    public function getBillingDetails()
    {
        return $this->billingDetails;
    }

    /**
     * @param AddressDetails $billingDetails
     * @return Order
     */
    public function setBillingDetails($billingDetails)
    {
        $this->billingDetails = $billingDetails;
        return $this;
    }

    /**
     * @return OrderItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param OrderItem[] $items
     * @return Order
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @param OrderItem $item
     * @return $this
     */
    public function addItem(OrderItem $item)
    {
        $this->items[] = $item;
        return $this;
    }
}