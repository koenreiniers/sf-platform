<?php
namespace Raw\Component\Admin\Easy\Element;

use Raw\Component\Admin\Easy\Element;
use Raw\Component\Admin\Easy\AutoEndElementInterface;
use Raw\Component\Admin\Layout\Definition\Builder\GridNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\NodeDefinition;

class GridElement extends Element implements AutoEndElementInterface
{
    public function setName($name)
    {
        return $this->setAttribute('gridName', $name);
    }

    public function setGridParameters(array $parameters)
    {
        return $this->setAttribute('gridParameters', $parameters);
    }

}