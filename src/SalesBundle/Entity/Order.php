<?php
namespace SalesBundle\Entity;

use AppBundle\Entity\Store;
use CrmBundle\Entity\Address;
use CrmBundle\Entity\Customer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Order
{
    const STATE_NEW = 'new';
    const STATE_PROCESSING = 'processing';
    const STATE_COMPLETED = 'completed';
    const STATE_CANCELLED = 'cancelled';
    const STATE_CLOSED = 'closed';
    const STATE_ON_HOLD = 'on_hold';

    public function __toString()
    {
        return 'Order #'.$this->orderNumber;
    }

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $externalId;

    /**
     * @var \DateTime
     */
    private $externalCreatedAt;

    /**
     * @var Store
     */
    private $store;

    /**
     * @var string
     */
    private $state;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var OrderItem[]|Collection
     */
    private $orderItems;

    /**
     * @var Shipment[]|Collection
     */
    private $shipments;

    /**
     * @var string
     */
    private $orderNumber;

    /**
     * @var \DateTime
     */
    private $orderDate;

    /**
     * @var Address
     */
    private $shippingAddress;

    /**
     * @var Address
     */
    private $billingAddress;

    /**
     * @var float
     */
    private $grandTotal;

    /**
     * @var float
     */
    private $subtotal;

    /**
     * @var float
     */
    private $taxAmount;

    /**
     * @var float
     */
    private $shippingAmount;

    /**
     * @var float
     */
    private $discountAmount;

    /**
     * @var Customer|null
     */
    private $customer;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
        $this->shipments = new ArrayCollection();
    }

    /**
     * @return Address
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @param Address $shippingAddress
     * @return Order
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    /**
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param Address $billingAddress
     * @return Order
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
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
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param string $orderNumber
     * @return Order
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * @param \DateTime $orderDate
     * @return Order
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;
        return $this;
    }




    /**
     * @return ArrayCollection|Collection|OrderItem[]
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $item)
    {
        $this->orderItems[] = $item;
        return $this;
    }

    public function removeOrderItem(OrderItem $item)
    {
        $this->orderItems->removeElement($item);
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
     * @return Order
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExternalCreatedAt()
    {
        return $this->externalCreatedAt;
    }

    /**
     * @param \DateTime $externalCreatedAt
     * @return Order
     */
    public function setExternalCreatedAt($externalCreatedAt)
    {
        $this->externalCreatedAt = $externalCreatedAt;
        return $this;
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
     * @return Order
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }




    /**
     * @return Store
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * @param Store $store
     * @return Order
     */
    public function setStore($store)
    {
        $this->store = $store;
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
     * @return Order
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    public function getShipments()
    {
        return $this->shipments;
    }

    public function addShipment(Shipment $shipment)
    {
        $this->shipments[] = $shipment;
    }

    public function removeShipment(Shipment $shipment)
    {
        $this->shipments->removeElement($shipment);
        return $this;
    }

    /**
     * @return float
     */
    public function getGrandTotal()
    {
        return $this->grandTotal;
    }

    /**
     * @param float $grandTotal
     * @return Order
     */
    public function setGrandTotal($grandTotal)
    {
        $this->grandTotal = $grandTotal;
        return $this;
    }

    /**
     * @return float
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * @param float $subtotal
     * @return Order
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;
        return $this;
    }

    /**
     * @return float
     */
    public function getTaxAmount()
    {
        return $this->taxAmount;
    }

    /**
     * @param float $taxAmount
     * @return Order
     */
    public function setTaxAmount($taxAmount)
    {
        $this->taxAmount = $taxAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getShippingAmount()
    {
        return $this->shippingAmount;
    }

    /**
     * @param float $shippingAmount
     * @return Order
     */
    public function setShippingAmount($shippingAmount)
    {
        $this->shippingAmount = $shippingAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getDiscountAmount()
    {
        return $this->discountAmount;
    }

    /**
     * @param float $discountAmount
     * @return Order
     */
    public function setDiscountAmount($discountAmount)
    {
        $this->discountAmount = $discountAmount;
        return $this;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer|null $customer
     * @return Order
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }




}