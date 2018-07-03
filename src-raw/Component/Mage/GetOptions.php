<?php
namespace Raw\Component\Mage;

class GetOptions
{
    /**
     * @var array
     */
    private $options = [];

    /**
     * RestOptions constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @param array $options
     * @return GetOptions
     */
    public static function create(array $options = [])
    {
        return new static($options);
    }

    public function set($name, $value)
    {
        $this->options[$name] = $value;
        return $this;
    }

    public function page($value)
    {
        $this->set('page', $value);
        return $this;
    }

    public function has($name)
    {
        return isset($this->options[$name]);
    }

    public function get($name, $default = null)
    {
        if(!$this->has($name)) {
            return $default;
        }
        return $this->options[$name];
    }

    public function addFilter($attribute, $operator, $value)
    {
        $filters = $this->get('filter', []);
        $filters[] = ['attribute' => $attribute, $operator => $value];
        return $this->set('filter', $filters);
    }

    public function limit($value)
    {
        return $this->set('limit', $value);
    }

    public function all()
    {
        return $this->options;
    }

}