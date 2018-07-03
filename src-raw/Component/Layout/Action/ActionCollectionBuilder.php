<?php
namespace Raw\Component\Layout\Action;

class ActionCollectionBuilder extends ActionCollection
{
    /**
     * @var bool
     */
    private $built = false;


    public function callbackAdd($name, callable $callback)
    {
        $action = new Action($name);
        $callback($action);
        return $this->addAction($action);
    }

    public function add($name, $type, $label)
    {
        $action = new Action($name);
        $action->setType($type);
        $action->setLabel($label);
        $this->addAction($action);
        return $action;
    }

    public function addAction(Action $action)
    {
        $this->verifyNotBuilt();
        $this->actions[$action->getName()] = $action;
        return $this;
    }

    private function verifyNotBuilt()
    {
        if($this->built) {
            throw new \Exception('Collection already built');
        }
    }

    public function getCollection()
    {
        $this->verifyNotBuilt();
        $collection = clone $this;
        $collection->built = true;
        return $collection;
    }
}