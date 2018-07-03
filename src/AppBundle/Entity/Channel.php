<?php
namespace AppBundle\Entity;

use CrmBundle\Entity\Customer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Channel
{
    public function __toString()
    {
        if($this->name !== null) {
            return $this->name;
        }
        return 'New channel';
    }

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $platformName;

    /**
     * @var Store[]|Collection
     */
    private $stores;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var \Doctrine\Common\Collections\Collection|Customer[]
     */
    private $customers;

    public function __construct()
    {
        $this->stores = new ArrayCollection();
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Channel
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlatformName()
    {
        return $this->platformName;
    }

    /**
     * @param string $platformName
     * @return Channel
     */
    public function setPlatformName($platformName)
    {
        $this->platformName = $platformName;
        return $this;
    }

    /**
     * @return Store[]|Collection
     */
    public function getStores()
    {
        return $this->stores;
    }

    public function addStore(Store $store)
    {
        $this->stores[] = $store;
        return $this;
    }

    public function removeStore(Store $store)
    {
        $this->stores->removeElement($store);
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;
        return $this;
    }

    /**
     * @param array $parameters
     * @return Channel
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->parameters[$name];
    }



    /**
     * Add customer
     *
     * @param \CrmBundle\Entity\Customer $customer
     *
     * @return Channel
     */
    public function addCustomer(\CrmBundle\Entity\Customer $customer)
    {
        $this->customers[] = $customer;

        return $this;
    }

    /**
     * Remove customer
     *
     * @param \CrmBundle\Entity\Customer $customer
     */
    public function removeCustomer(\CrmBundle\Entity\Customer $customer)
    {
        $this->customers->removeElement($customer);
    }

    /**
     * Get customers
     *
     * @return \Doctrine\Common\Collections\Collection|Customer[]
     */
    public function getCustomers()
    {
        return $this->customers;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $products;


    /**
     * Add product
     *
     * @param \CatalogBundle\Entity\Product $product
     *
     * @return Channel
     */
    public function addProduct(\CatalogBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \CatalogBundle\Entity\Product $product
     */
    public function removeProduct(\CatalogBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}
