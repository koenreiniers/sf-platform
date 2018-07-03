<?php
namespace Raw\Bundle\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Raw\Bundle\PlatformBundle\Entity\File;
use Raw\Bundle\VersioningBundle\Behaviour\VersionableInterface;

class User extends BaseUser implements VersionableInterface
{

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var Collection|Notification[]
     */
    protected $notifications;

    /**
     * @var File|null
     */
    protected $profileImage;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    public function __construct()
    {
        parent::__construct();
        $this->notifications = new ArrayCollection();
    }

    /**
     * @return UserGroup[]|ArrayCollection|\Traversable
     */
    public function getGroups()
    {
        return parent::getGroups();
    }

    public function getGroupCodes()
    {
        $codes = [];
        foreach($this->getGroups() as $userGroup) {
            $codes[] = $userGroup->getCode();
        }
        return $codes;
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
     * @return User
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }

    /**
     * @param File $profileImage
     * @return User
     */
    public function setProfileImage($profileImage)
    {
        $this->profileImage = $profileImage;
        return $this;
    }

    public function __toString()
    {
        return $this->username;
    }


    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->firstName.' '.$this->lastName;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param Notification $notification
     * @return $this
     */
    public function addNotification(Notification $notification)
    {
        $this->notifications[] = $notification;
        return $this;
    }

    /**
     * @param Notification $notification
     * @return $this
     */
    public function removeNotification(Notification $notification)
    {
        $this->notifications->removeElement($notification);
        return $this;
    }
}