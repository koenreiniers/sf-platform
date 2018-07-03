<?php
namespace CatalogBundle\Entity;

use AppBundle\Entity\Channel;
use AppBundle\Entity\Store;

class Product
{
    public function __toString()
    {
        return $this->name;
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
     * @var Channel
     */
    private $channel;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $sku;

    /**
     * @var string
     */
    private $name;

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
     * @return Product
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
     * @return Product
     */
    public function setExternalCreatedAt($externalCreatedAt)
    {
        $this->externalCreatedAt = $externalCreatedAt;
        return $this;
    }

    /**
     * @return Channel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param Channel $channel
     * @return Product
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
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
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;
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
     * @return Product
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
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }




    /**
     * @var \DateTime
     */
    private $externalUpdatedAt;


    /**
     * Set externalUpdatedAt
     *
     * @param \DateTime $externalUpdatedAt
     *
     * @return Product
     */
    public function setExternalUpdatedAt($externalUpdatedAt)
    {
        $this->externalUpdatedAt = $externalUpdatedAt;

        return $this;
    }

    /**
     * Get externalUpdatedAt
     *
     * @return \DateTime
     */
    public function getExternalUpdatedAt()
    {
        return $this->externalUpdatedAt;
    }
    /**
     * @var string
     */
    private $weight;

    /**
     * @var string
     */
    private $stockQty;


    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return Product
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set stockQty
     *
     * @param string $stockQty
     *
     * @return Product
     */
    public function setStockQty($stockQty)
    {
        $this->stockQty = $stockQty;

        return $this;
    }

    /**
     * Get stockQty
     *
     * @return string
     */
    public function getStockQty()
    {
        return $this->stockQty;
    }
}
