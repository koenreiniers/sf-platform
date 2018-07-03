<?php
namespace Raw\Bundle\VersioningBundle\Entity;

class Version
{
    const CONTEXT_UPDATE = 'update';
    const CONTEXT_RESTORE = 'restore';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $resourceName;

    /**
     * @var int
     */
    private $resourceId;

    /**
     * @var array
     */
    private $snapshot;

    /**
     * @var array
     */
    private $changeSet = [];

    /**
     * @var int
     */
    private $number = 1;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $context = self::CONTEXT_UPDATE;

    /**
     * @var string
     */
    private $author;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     * @return Version
     */
    public function setContext($context)
    {
        $this->context = $context;
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
     * @return Version
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return Version
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceName()
    {
        return $this->resourceName;
    }

    /**
     * @param string $resourceName
     * @return Version
     */
    public function setResourceName($resourceName)
    {
        $this->resourceName = $resourceName;
        return $this;
    }

    /**
     * @return int
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @param int $resourceId
     * @return Version
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;
        return $this;
    }

    /**
     * @return array
     */
    public function getSnapshot()
    {
        return $this->snapshot;
    }

    /**
     * @param array $snapshot
     * @return Version
     */
    public function setSnapshot($snapshot)
    {
        $this->snapshot = $snapshot;
        return $this;
    }

    /**
     * @return array
     */
    public function getChangeSet()
    {
        return $this->changeSet;
    }

    /**
     * @param array $changeSet
     * @return Version
     */
    public function setChangeSet($changeSet)
    {
        $this->changeSet = $changeSet;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return Version
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }
}