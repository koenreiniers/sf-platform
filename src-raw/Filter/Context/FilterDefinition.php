<?php
namespace Raw\Filter\Context;

class FilterDefinition
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $options = [];

    /**
     * FilterDefinition constructor.
     * @param string $type
     * @param array $options
     */
    public function __construct($fieldName, $type, array $options = [])
    {
        $this->fieldName = $fieldName;
        $this->type = $type;
        $this->options = $options;
    }

    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getOption($name)
    {
        return $this->options[$name];
    }
}