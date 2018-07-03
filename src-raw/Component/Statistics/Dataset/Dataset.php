<?php
namespace Raw\Component\Statistics\Dataset;

use Raw\Component\Statistics\Statistic;

class Dataset extends Statistic
{
    /**
     * @var array
     */
    protected $dataPoints;

    /**
     * Dataset constructor.
     * @param array $dataPoints
     */
    public function __construct(array $dataPoints = [])
    {
        $this->dataPoints = $dataPoints;
    }

    /**
     * @return array
     */
    public function getDataPoints(array $parameters)
    {
        return $this->dataPoints;
    }

    /**
     * @param array $labels
     * @param int $emptyValue
     * @return array
     */
    protected function createEmptyDataPoints(array $labels, $emptyValue = 0)
    {
        $dataPoints = [];
        foreach($labels as $label) {
            $dataPoints[$label] = $emptyValue;
        }
        return $dataPoints;
    }
}