<?php
namespace Raw\Component\Admin\Layout\Definition\Builder;

class LayoutBuilder
{
    /**
     * @param string $name
     * @param string $nodeClass
     * @return LayoutDefinition
     */
    public function root($name, $nodeClass = LayoutDefinition::class)
    {
        $rootNode = new $nodeClass($name);
        return $rootNode;
    }
}