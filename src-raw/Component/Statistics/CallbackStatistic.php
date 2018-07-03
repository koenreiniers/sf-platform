<?php
namespace Raw\Component\Statistics;

class CallbackStatistic extends Statistic
{

    /**
     * @var callable
     */
    private $callback;

    /**
     * CallbackStatistic constructor.
     * @param callable $callback
     * @param array $options
     */
    public function __construct(callable $callback, array $options = [])
    {
        $this->callback = $callback;
        parent::__construct(null, $options);
    }

    public function getData(array $parameters = [])
    {

            $cb = $this->callback;
            $data = $cb($parameters);
        return $data;
    }
}