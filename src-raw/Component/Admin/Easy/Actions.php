<?php
namespace Raw\Component\Admin\Easy;

class Actions
{
    /**
     * @var array
     */
    private $actions = [];

    /**
     * Actions constructor.
     * @param array $actions
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function add($name)
    {
        $this->actions[] = $name;
        return $this;
    }

    public function remove($name)
    {
        throw new \Exception('todo');
    }

    public function all()
    {
        return $this->actions;
    }
}