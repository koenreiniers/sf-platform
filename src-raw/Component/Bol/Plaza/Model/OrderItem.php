<?php
namespace Raw\Component\Bol\Plaza\Model;

class OrderItem
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $ean;

    /**
     * @var string
     */
    protected $offerReference;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var float
     */
    protected $offerPrice;

    /**
     * @var float
     */
    protected $transactionFee;

    /**
     * @var \DateTime
     */
    protected $promisedDeliveryDate;

    /**
     * @var string
     */
    protected $offerCondition;

    /**
     * @var bool
     */
    protected $cancelRequest;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return OrderItem
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * @param string $ean
     * @return OrderItem
     */
    public function setEan($ean)
    {
        $this->ean = $ean;
        return $this;
    }

    /**
     * @return string
     */
    public function getOfferReference()
    {
        return $this->offerReference;
    }

    /**
     * @param string $offerReference
     * @return OrderItem
     */
    public function setOfferReference($offerReference)
    {
        $this->offerReference = $offerReference;
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
     * @return OrderItem
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return OrderItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return float
     */
    public function getOfferPrice()
    {
        return $this->offerPrice;
    }

    /**
     * @param float $offerPrice
     * @return OrderItem
     */
    public function setOfferPrice($offerPrice)
    {
        $this->offerPrice = $offerPrice;
        return $this;
    }

    /**
     * @return float
     */
    public function getTransactionFee()
    {
        return $this->transactionFee;
    }

    /**
     * @param float $transactionFee
     * @return OrderItem
     */
    public function setTransactionFee($transactionFee)
    {
        $this->transactionFee = $transactionFee;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPromisedDeliveryDate()
    {
        return $this->promisedDeliveryDate;
    }

    /**
     * @param \DateTime $promisedDeliveryDate
     * @return OrderItem
     */
    public function setPromisedDeliveryDate($promisedDeliveryDate)
    {
        $this->promisedDeliveryDate = $promisedDeliveryDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getOfferCondition()
    {
        return $this->offerCondition;
    }

    /**
     * @param string $offerCondition
     * @return OrderItem
     */
    public function setOfferCondition($offerCondition)
    {
        $this->offerCondition = $offerCondition;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isCancelRequest()
    {
        return $this->cancelRequest;
    }

    /**
     * @param boolean $cancelRequest
     * @return OrderItem
     */
    public function setCancelRequest($cancelRequest)
    {
        $this->cancelRequest = $cancelRequest;
        return $this;
    }
    
    
}