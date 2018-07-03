<?php
namespace Raw\Filter;

use Raw\Filter\Exception\FilterValidationException;
use Raw\Filter\Type\BaseFilterType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Filter implements \Serializable
{
    public function serialize()
    {
        return serialize([$this->field, $this->operator, $this->data]);
    }
    public function unserialize($serialized)
    {
        list($this->field, $this->operator, $this->data) = unserialize($serialized);
    }

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var array
     */
    private $data = [];



    /**
     * Filter constructor.
     * @param string $name
     * @param string $operator
     * @param array $data
     */
    public function __construct($name = null, $operator = null, $data = [])
    {
        $this->field = $name;
        $this->operator = $operator;
        $this->data = (array)$data;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->field;
    }


    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $field
     * @return Filter
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @param string $operator
     * @return Filter
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * @param array $data
     * @return Filter
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }


}