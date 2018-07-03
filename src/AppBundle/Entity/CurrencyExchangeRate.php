<?php
namespace AppBundle\Entity;

class CurrencyExchangeRate
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    /**
     * @var float
     */
    private $rate = 1;

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
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return ExchangeRate
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return ExchangeRate
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     * @return ExchangeRate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }
}