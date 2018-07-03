<?php
namespace Raw\Component\Bol\Plaza\Model;

class Offer
{
    const CONDITION_NEW         = 'NEW';
    const CONDITION_AS_NEW      = 'AS_NEW';
    const CONDITION_GOOD        = 'GOOD';
    const CONDITION_REASONABLE  = 'REASONABLE';
    const CONDITION_MODERATE    = 'MODERATE';

    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $ean;

    /**
     * @var string
     */
    private $condition;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $deliveryCode;

    /**
     * @var int
     */
    private $quantityInStock;

    /**
     * @var bool
     */
    private $publish;

    /**
     * @var string
     */
    private $referenceCode;

    /**
     * @var string
     */
    private $description;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Offer
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }



    /**
     * @return int
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * @param int $ean
     * @return Offer
     */
    public function setEan($ean)
    {
        $this->ean = $ean;
        return $this;
    }

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     * @return Offer
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
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
     * @return Offer
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryCode()
    {
        return $this->deliveryCode;
    }

    /**
     * @param string $deliveryCode
     * @return Offer
     */
    public function setDeliveryCode($deliveryCode)
    {
        $this->deliveryCode = $deliveryCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantityInStock()
    {
        return $this->quantityInStock;
    }

    /**
     * @param int $quantityInStock
     * @return Offer
     */
    public function setQuantityInStock($quantityInStock)
    {
        $this->quantityInStock = $quantityInStock;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublish()
    {
        return $this->publish;
    }

    /**
     * @param boolean $publish
     * @return Offer
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;
        return $this;
    }

    /**
     * @return string
     */
    public function getReferenceCode()
    {
        return $this->referenceCode;
    }

    /**
     * @param string $referenceCode
     * @return Offer
     */
    public function setReferenceCode($referenceCode)
    {
        $this->referenceCode = $referenceCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Offer
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}