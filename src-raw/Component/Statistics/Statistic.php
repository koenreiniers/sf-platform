<?php
namespace Raw\Component\Statistics;

class Statistic
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * Statistic constructor.
     * @param mixed $data
     * @param array $options
     */
    public function __construct($data, array $options = [])
    {
        $this->data = $data;
        $this->options = $this->resolveOptions($options);
    }

    public function getData(array $parameters = [])
    {
        return $this->data;
    }

    public function setLabel($label)
    {
        $this->options['label'] = $label;
        return $this;
    }

    public function getLabel()
    {
        return $this->options['label'];
    }

    public function getOption($name)
    {
        return $this->options[$name];
    }

    private function resolveOptions(array $options)
    {
        $defaults = [
            'label' => null,
            'cache_ttl' => null,
            'url' => null,
        ];
        $options = array_merge($defaults, $options);
        return $options;
    }
}