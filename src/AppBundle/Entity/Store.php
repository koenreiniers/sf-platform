<?php
namespace AppBundle\Entity;


use CatalogBundle\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SalesBundle\Entity\Order;

class Store
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
     * @var Channel
     */
    private $channel;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Order[]|Collection
     */
    private $orders;

    /**
     * @var string
     */
    private $currencyCode = 'EUR';

    /**
     * Store constructor.
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->products = new ArrayCollection();
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
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     * @return Store
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
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
     * @return Store
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
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
     * @return Store
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
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
     * @return Store
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Order[]
     */
    public function getOrders()
    {
        return $this->orders;
    }

    public function addOrder(Order $order)
    {
        $this->orders[] = $order;
        return $this;
    }

    public function removeOrder(Order $order)
    {
        $this->orders->removeElement($order);
        return $this;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function addProduct(Product $product)
    {
        $this->products[] = $product;
        return $this;
    }

    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
        return $this;
    }

}