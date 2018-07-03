<?php
namespace Raw\Component\Statistics;

use Raw\Component\Statistics\Exception\StatisticNotFoundException;

class StatisticManager
{

    /**
     * @var StatisticLoaderInterface
     */
    private $loader;

    /**
     * @var StatisticCollection|null
     */
    private $statistics;

    /**
     * StatisticManager constructor.
     * @param StatisticLoaderInterface $loader
     */
    public function __construct(StatisticLoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    private function load()
    {
        $this->statistics = new StatisticCollection();
        $this->loader->load($this->statistics);
    }

    /**
     * @return StatisticCollection
     */
    public function getStatisticCollection()
    {
        if($this->statistics === null) {
            $this->load();
        }
        return $this->statistics;
    }

    /**
     * @param string $name
     *
     * @return Statistic
     *
     * @throws StatisticNotFoundException
     */
    public function getStatistic($name)
    {
        if(!$this->hasStatistic($name)) {
            throw new StatisticNotFoundException($name);
        }
        return $this->getStatisticCollection()->get($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasStatistic($name)
    {
        return $this->getStatisticCollection()->has($name);
    }
}