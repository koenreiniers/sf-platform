<?php
namespace Raw\Component\Admin\Layout\Definition\Builder;

use Raw\Component\Admin\Layout\Definition\FieldsetNode;

class FieldsetNodeDefinition extends ArrayNodeDefinition
{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->title($name);
    }

    protected function instantiateLayout()
    {
        return new FieldsetNode($this->name, $this->parent);
    }

    public function title($title)
    {
        return $this->attribute('title', $title);
    }
}