<?php
namespace Raw\Component\Admin\Layout\Definition\Builder;

use Raw\Component\Admin\Layout\Definition\FormRowNode;

class FormRowNodeDefinition extends NodeDefinition
{
    protected function instantiateLayout()
    {
        return new FormRowNode($this->name, $this->parent);
    }
}