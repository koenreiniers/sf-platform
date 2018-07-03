<?php
namespace Raw\Bundle\ApiBundle\Envelope\Data;

class LazyData extends Data
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * LazyData constructor.
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function get()
    {
        if(!$this->initialized) {
            $callback = $this->callback;
            $this->data = $callback();
        }
        return $this->data;
    }
}