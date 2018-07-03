<?php
namespace Raw\Component\Statistics\Dataset;

class CallbackDataset extends Dataset
{

    /**
     * @var callable
     */
    protected $callback;

    /**
     * CallbackDataset constructor.
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return array
     */
    public function getDataPoints(array $parameters)
    {
        $cb = $this->callback;
        $data = $cb($parameters);
        return $data;
    }
}