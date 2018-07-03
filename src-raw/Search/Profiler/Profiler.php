<?php
namespace Raw\Search\Profiler;

use Raw\Search\SearchIndex;
use Symfony\Component\Stopwatch\Stopwatch;

class Profiler
{
    /**
     * @var Stopwatch
     */
    private $stopwatch;

    /**
     * @var SearchIndex
     */
    private $index;

    /**
     * Profiler constructor.
     */
    public function __construct(SearchIndex $index)
    {
        $this->index = $index;
        $this->stopwatch = new Stopwatch();
    }

    public function getStopwatch()
    {
        return $this->stopwatch;
    }

    public function start($event)
    {
        $this->stopwatch->start($event);
    }

    public function stop($event)
    {
        $this->stopwatch->stop($event);
    }
}