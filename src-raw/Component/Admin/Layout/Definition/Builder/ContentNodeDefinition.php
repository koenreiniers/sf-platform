<?php
namespace Raw\Component\Admin\Layout\Definition\Builder;

class ContentNodeDefinition extends ArrayNodeDefinition
{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->title($name);
    }

    public function title($title)
    {
        return $this->attribute('title', $title);
    }
}