<?php
namespace AppBundle\Currency;

use AppBundle\Settings\Settings;
use Doctrine\ORM\EntityManager;
use Symfony\Component\VarDumper\VarDumper;

class ExchangeRatesTable implements \Serializable
{

    public function serialize()
    {
        return serialize($this->table);
    }

    public function unserialize($serialized)
    {
        $this->table = unserialize($serialized);
    }

    /**
     * @var array
     */
    private $table;

    /**
     * ExchangeRatesTable constructor.
     * @param array $table
     */
    public function __construct(array $table)
    {
        $this->table = $table;
    }

    /**
     * @param string $from
     * @param string $to
     * @return float|null
     */
    public function getRate($from, $to)
    {
        if(!isset($this->table[$from])) {
            return null;
        }
        $rates = $this->table[$from];
        if(!isset($rates[$to])) {
            return null;
        }
        return $rates[$to];
    }
}