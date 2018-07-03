<?php
namespace Raw\Component\Statistics;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class StatisticCollection implements \IteratorAggregate
{
    /**
     * @var Collection
     */
    private $wrapped;

    public function getIterator()
    {
        return $this->wrapped->getIterator();
    }

    /**
     * StatisticCollection constructor.
     */
    public function __construct()
    {
        $this->wrapped = new ArrayCollection();
    }

    public function add($name, Statistic $statistic)
    {
        if($statistic->getLabel() === null) {
            $statistic->setLabel($name);
        }
        $this->wrapped->set($name, $statistic);
        return $this;
    }

    /**
     * @return Statistic[]
     */
    public function all()
    {
        return $this->wrapped->getValues();
    }

    /**
     * @param string $name
     * @return Statistic
     */
    public function get($name)
    {
        return $this->wrapped->get($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return $this->wrapped->containsKey($name);
    }
}