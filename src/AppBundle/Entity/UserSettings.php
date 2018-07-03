<?php
namespace AppBundle\Entity;

class UserSettings
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $decimalSeparator = ',';

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
    public function getDecimalSeparator()
    {
        return $this->decimalSeparator;
    }

    /**
     * @param string $decimalSeparator
     * @return UserSettings
     */
    public function setDecimalSeparator($decimalSeparator)
    {
        $this->decimalSeparator = $decimalSeparator;
        return $this;
    }


}