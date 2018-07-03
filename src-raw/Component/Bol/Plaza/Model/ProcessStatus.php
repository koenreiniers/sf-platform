<?php
namespace Raw\Component\Bol\Plaza\Model;

class ProcessStatus
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $sellerId;

    /**
     * @var string|null
     */
    protected $entityId;

    /**
     * @var string
     */
    protected $eventType;

    /**
     * @var string
     */
    protected $description;

    /**
     * One of: PENDING, SUCCESS, FAILURE, TIMEOUT
     *
     * @var string
     */
    protected $status;

    /**
     * @var string|null
     */
    protected $errorMessage;

    /**
     * @var \DateTime
     */
    protected $createTimestamp;

    /**
     * @var array
     */
    protected $links;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ProcessStatus
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getSellerId()
    {
        return $this->sellerId;
    }

    /**
     * @param int $sellerId
     * @return ProcessStatus
     */
    public function setSellerId($sellerId)
    {
        $this->sellerId = $sellerId;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param null|string $entityId
     * @return ProcessStatus
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
        return $this;
    }

    /**
     * @return string
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @param string $eventType
     * @return ProcessStatus
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
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
     * @return ProcessStatus
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return ProcessStatus
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param null|string $errorMessage
     * @return ProcessStatus
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreateTimestamp()
    {
        return $this->createTimestamp;
    }

    /**
     * @param \DateTime $createTimestamp
     * @return ProcessStatus
     */
    public function setCreateTimestamp($createTimestamp)
    {
        $this->createTimestamp = $createTimestamp;
        return $this;
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param array $links
     * @return ProcessStatus
     */
    public function setLinks($links)
    {
        $this->links = $links;
        return $this;
    }
}