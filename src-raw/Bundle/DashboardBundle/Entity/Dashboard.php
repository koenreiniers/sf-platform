<?php
namespace Raw\Bundle\DashboardBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Raw\Bundle\UserBundle\Behaviour\OwnableInterface;
use Raw\Bundle\UserBundle\Behaviour\OwnableTrait;

class Dashboard implements OwnableInterface
{
    use OwnableTrait;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Widget[]|Collection
     */
    private $widgets;

    /**
     * @var bool
     */
    private $default = false;

    public function __construct()
    {
        $this->widgets = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * @param boolean $default
     * @return Dashboard
     */
    public function setDefault($default)
    {
        $this->default = $default;
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
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Widget[]
     */
    public function getWidgets()
    {
        return $this->widgets;
    }

    public function addWidget(Widget $widget)
    {
        $this->widgets[] = $widget;
        return $this;
    }

    public function removeWidget(Widget $widget)
    {
        $this->widgets->removeElement($widget);
        return $this;
    }
}