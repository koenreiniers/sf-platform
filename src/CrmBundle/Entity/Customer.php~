<?php

namespace CrmBundle\Entity;
use CrmBundle\Contact\ContactTrait;

/**
 * Customer
 */
class Customer
{
    use ContactTrait;

    public function __toString()
    {
        return $this->getFullName();
    }

    /**
     * @var integer
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
     * @var \DateTime
     */
    private $externalUpdatedAt;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $addresses;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set externalId
     *
     * @param string $externalId
     *
     * @return Customer
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get externalId
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * Set externalCreatedAt
     *
     * @param \DateTime $externalCreatedAt
     *
     * @return Customer
     */
    public function setExternalCreatedAt($externalCreatedAt)
    {
        $this->externalCreatedAt = $externalCreatedAt;

        return $this;
    }

    /**
     * Get externalCreatedAt
     *
     * @return \DateTime
     */
    public function getExternalCreatedAt()
    {
        return $this->externalCreatedAt;
    }

    /**
     * Set externalUpdatedAt
     *
     * @param \DateTime $externalUpdatedAt
     *
     * @return Customer
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Customer
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Customer
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Add address
     *
     * @param \CrmBundle\Entity\Address $address
     *
     * @return Customer
     */
    public function addAddress(\CrmBundle\Entity\Address $address)
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Remove address
     *
     * @param \CrmBundle\Entity\Address $address
     */
    public function removeAddress(\CrmBundle\Entity\Address $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }
    /**
     * @var \AppBundle\Entity\Channel
     */
    private $channel;


    /**
     * Set channel
     *
     * @param \AppBundle\Entity\Channel $channel
     *
     * @return Customer
     */
    public function setChannel(\AppBundle\Entity\Channel $channel = null)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return \AppBundle\Entity\Channel
     */
    public function getChannel()
    {
        return $this->channel;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $orders;


    /**
     * Add order
     *
     * @param \SalesBundle\Entity\Order $order
     *
     * @return Customer
     */
    public function addOrder(\SalesBundle\Entity\Order $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \SalesBundle\Entity\Order $order
     */
    public function removeOrder(\SalesBundle\Entity\Order $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }
    /**
     * @var string
     */
    private $namePrefix;

    /**
     * @var string
     */
    private $nameSuffix;

    /**
     * @var string
     */
    private $middleName;


    /**
     * Set namePrefix
     *
     * @param string $namePrefix
     *
     * @return Customer
     */
    public function setNamePrefix($namePrefix)
    {
        $this->namePrefix = $namePrefix;

        return $this;
    }

    /**
     * Get namePrefix
     *
     * @return string
     */
    public function getNamePrefix()
    {
        return $this->namePrefix;
    }

    /**
     * Set nameSuffix
     *
     * @param string $nameSuffix
     *
     * @return Customer
     */
    public function setNameSuffix($nameSuffix)
    {
        $this->nameSuffix = $nameSuffix;

        return $this;
    }

    /**
     * Get nameSuffix
     *
     * @return string
     */
    public function getNameSuffix()
    {
        return $this->nameSuffix;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     *
     * @return Customer
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }
}
