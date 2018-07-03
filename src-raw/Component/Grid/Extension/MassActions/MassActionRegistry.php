<?php
namespace Raw\Component\Grid\Extension\MassActions;

class MassActionRegistry
{
    /**
     * @var MassAction[]
     */
    private $massActions;

    /**
     * MassActionRegistry constructor.
     * @param MassAction[] $massActions
     */
    public function __construct(array $massActions)
    {
        $this->massActions = $massActions;
    }

    public function getMassAction($name)
    {
        if(!$this->hasMassAction($name)) {
            throw new \Exception(sprintf('Mass action "%s" does not exist', $name));
        }
        return $this->massActions[$name];
    }

    public function hasMassAction($name)
    {
        return isset($this->massActions[$name]);
    }
}