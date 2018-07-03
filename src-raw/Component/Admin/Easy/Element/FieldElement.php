<?php
namespace Raw\Component\Admin\Easy\Element;

use Raw\Component\Admin\Easy\Element;
use Raw\Component\Admin\Easy\AutoEndElementInterface;
use Raw\Component\Admin\Layout\Definition\Builder\FormRowNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\NodeDefinition;

class FieldElement extends Element implements AutoEndElementInterface
{
    public function getName()
    {
        return $this->getAttribute('name');
    }
}