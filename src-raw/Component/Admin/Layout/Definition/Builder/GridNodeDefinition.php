<?php
namespace Raw\Component\Admin\Layout\Definition\Builder;

use Raw\Component\Admin\Layout\Definition\GridNode;

class GridNodeDefinition extends NodeDefinition
{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->gridName($name);
        $this->gridParameters([]);
    }

    /**
     * @param string $gridName
     * @return $this
     */
    public function gridName($gridName)
    {
        return $this->attribute('gridName', $gridName);
    }

    public function gridParameters(array $parameters)
    {
        return $this->attribute('gridParameters', $parameters);
    }

    protected function instantiateLayout()
    {
        return new GridNode($this->name, $this->parent);
    }
}