<?php
namespace Raw\Component\Admin\Easy\Element;

use Raw\Component\Admin\Easy\Element;
use Raw\Component\Admin\Layout\Definition\Builder\NodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\TabsNodeDefinition;

class TabsetElement extends Element
{
    /**
     * @param string $name
     * @return TabElement
     */
    public function addTab($name)
    {
        return $this->add(TabElement::class, [
            'name' => $name,
            'title' => $name,
        ]);
    }

    /**
     * @return \Raw\Component\Admin\Easy\Element
     */
    public function endTabset()
    {
        return $this->end();
    }
}