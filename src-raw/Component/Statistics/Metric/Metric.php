<?php
namespace Raw\Component\Statistics\Metric;

class Metric
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * Metric constructor.
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param \DateTime $start
     * @param \DateTime|null $end
     * @return array
     */
    public function getData(\DateTime $start, \DateTime $end = null)
    {
        if($end === null) {
            $end = new \DateTime();
        }
        $cb = $this->callback;

        $interval = $start->diff($end);

        $currentPeriod = new \DatePeriod($start, $interval, $end);

        $previousStart = clone $start;
        $previousStart->sub($interval);
        $previousPeriod = new \DatePeriod($previousStart, $interval, $start);

        $previousData = $cb($previousPeriod);
        $currentData = $cb($currentPeriod);
        $diff = $currentData - $previousData;
        $progress = 100;
        if($previousData != 0) {
            $progress = round($currentData / $previousData * 100, 2) - 100;
        }

        return [
            'previousData' => $previousData,
            'currentData' => $currentData,
            'diff' => $diff,
            'progress' => $progress,
        ];
    }
}