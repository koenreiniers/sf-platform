<?php
namespace Raw\Component\Layout\Action;

class Action
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string|null
     */
    private $url;

    /**
     * @var string|null
     */
    private $route;

    /**
     * @var array
     */
    private $routeParameters = [];

    /**
     * @var callable
     */
    private $routeParametersResolver;

    /**
     * @var string
     */
    private $level = 'default';

    /**
     * Action constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function setRouteParametersResolver(callable $callback)
    {
        $this->routeParametersResolver = $callback;
        return $this;
    }

    public function createView($entity = null)
    {
        $view = [
            'name' => $this->name,
            'type' => $this->type,
            'label' => $this->label,
            'url' => $this->url,
            'route' => $this->route,
            'routeParameters' => $this->resolveRouteParameters($entity),
            'level' => $this->level,
        ];
        return $view;
    }

    public function resolveRouteParameters($entity = null)
    {
        if($entity !== null && $this->routeParametersResolver !== null) {
            $resolver = $this->routeParametersResolver;
            return $resolver($entity);
        }
        return $this->routeParameters;
    }

    /**
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param string $level
     * @return Action
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param null|string $route
     * @return Action
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * @param array $routeParameters
     * @return Action
     */
    public function setRouteParameters($routeParameters)
    {
        $this->routeParameters = $routeParameters;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Action
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Action
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Action
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     * @return Action
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
}