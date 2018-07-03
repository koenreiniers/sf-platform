<?php
namespace AppBundle\Settings;

class Settings
{
    /**
     * @var string
     */
    private $baseCurrency = 'EUR';

    /**
     * @return string
     */
    public function getBaseCurrency()
    {
        return $this->baseCurrency;
    }
}