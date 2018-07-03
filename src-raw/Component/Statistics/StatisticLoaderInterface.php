<?php
namespace Raw\Component\Statistics;

interface StatisticLoaderInterface
{
    public function load(StatisticCollection $statistics);
}