<?php
namespace Raw\Component\Admin;

class AdminPage
{
    /**
     * @var array
     */
    private $enabledActions;

    /**
     * @var callable
     */
    private $controller;

    /**
     * @param callable $controller
     * @return $this
     */
    public function setController(callable $controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return callable
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param array $actions
     * @return $this
     */
    public function disableActions(array $actions)
    {
        foreach($actions as $action) {
            $this->disableAction($action);
        }
        return $this;
    }

    private function disableAction($action)
    {
        if(!in_array($action, $this->enabledActions)) {
            return;
        }
        throw new \Exception('TODO');
    }

    private function enableAction($action)
    {
        if(in_array($action, $this->enabledActions)) {
            return $this;
        }
        $this->enabledActions[] = $action;
        return $this;
    }

    /**
     * @param array $actions
     * @return $this
     */
    public function enableActions(array $actions)
    {
        foreach($actions as $action) {
            $this->enableAction($action);
        }
        return $this;
    }
}