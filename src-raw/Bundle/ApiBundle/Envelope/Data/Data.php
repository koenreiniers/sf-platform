<?php
namespace Raw\Bundle\ApiBundle\Envelope\Data;

class Data
{
    /**
     * @var array|null
     */
    protected $data = null;

    /**
     * Data constructor.
     * @param array|null $data
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function get()
    {
        return $this->data;
    }
}