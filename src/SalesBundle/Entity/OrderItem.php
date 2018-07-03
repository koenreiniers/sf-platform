<?php
namespace SalesBundle\Entity;

class OrderItem
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
     * @var Order
     */
    private $order;

    /**
     * @var string
     */
    private $sku;

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $price;

    /**
     * @var float
     */
    private $qtyOrdered;

    /**
     * @var float
     */
    private $qtyShipped;

    /**
     * @var float
     */
    private $taxAmount;

    /**
     * @var float
     */
    private $taxPercent;

    /**
     * @var float
     */
    private $rowTotal;

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
     * @return OrderItem
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
     * @return OrderItem
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return OrderItem
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return OrderItem
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return OrderItem
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return float
     */
    public function getQtyOrdered()
    {
        return $this->qtyOrdered;
    }

    /**
     * @param float $qtyOrdered
     * @return OrderItem
     */
    public function setQtyOrdered($qtyOrdered)
    {
        $this->qtyOrdered = $qtyOrdered;
        return $this;
    }

    /**
     * @return float
     */
    public function getQtyShipped()
    {
        return $this->qtyShipped;
    }

    /**
     * @param float $qtyShipped
     * @return OrderItem
     */
    public function setQtyShipped($qtyShipped)
    {
        $this->qtyShipped = $qtyShipped;
        return $this;
    }

    /**
     * @return float
     */
    public function getQtyLeft()
    {
        return $this->qtyOrdered - $this->qtyShipped;
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
     * @return OrderItem
     */
    public function setTaxAmount($taxAmount)
    {
        $this->taxAmount = $taxAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getTaxPercent()
    {
        return $this->taxPercent;
    }

    /**
     * @param float $taxPercent
     * @return OrderItem
     */
    public function setTaxPercent($taxPercent)
    {
        $this->taxPercent = $taxPercent;
        return $this;
    }

    /**
     * @return float
     */
    public function getRowTotal()
    {
        return $this->rowTotal;
    }

    /**
     * @param float $rowTotal
     * @return OrderItem
     */
    public function setRowTotal($rowTotal)
    {
        $this->rowTotal = $rowTotal;
        return $this;
    }


}