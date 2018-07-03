<?php
namespace Raw\Component\Admin\Easy\Element;

use Raw\Component\Admin\Layout\Definition\Builder\NodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\TabNodeDefinition;

class TabElement extends LayoutElement
{
    /**
     * @return TabsetElement
     */
    public function endTab()
    {
        return $this->end();
    }
}