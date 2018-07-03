<?php
namespace Raw\Bundle\ApiBundle\Envelope;

use Raw\Bundle\ApiBundle\Envelope\Data\Data;

class Envelope
{
    /**
     * @var array
     */
    protected $data = null;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * Envelope constructor.
     * @param array $data
     * @param array $headers
     */
    public function __construct(array $data = null, array $headers = [])
    {
        $this->data = $data;
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $data
     * @return Envelope
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param array $headers
     * @return Envelope
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function setDataValue($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function getDataValue($key)
    {
        $value = $this->data[$key];
        if($value instanceof Data) {
            $value = $value->get();
        }
        return $value;
    }


}