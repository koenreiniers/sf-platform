<?php
namespace Raw\Component\Admin\Layout\Definition\Builder;

use Raw\Component\Admin\Layout\Definition\ActionNode;

class ActionNodeDefinition extends LayoutDefinition
{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->type($name);
        $this->label($name);
        $this->route($name, []);
        $this->level('default');
    }

    protected function instantiateLayout()
    {
        return new ActionNode($this->name, $this->parent);
    }

    /**
     * @param string $level
     * @return $this
     */
    public function level($level)
    {
        return $this->attribute('level', $level);
    }

    /**
     * @param $label
     * @return $this
     */
    public function label($label)
    {
        return $this->attribute('label', $label);
    }

    /**
     * @param string $route
     * @param array $routeParameters
     * @return $this
     */
    public function route($route, array $routeParameters = [])
    {
        $this->attribute('route', $route);
        $this->attribute('routeParameters', $routeParameters);
        return $this;
    }

    /**
     * @param $type
     * @return $this
     */
    public function type($type)
    {
        return $this->attribute('type', $type);
    }

    /**
     * @return NodeBuilder
     */
    public function end()
    {
        return parent::end(); // TODO: Change the autogenerated stub
    }
}