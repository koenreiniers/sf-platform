<?php
namespace Raw\Component\Admin\Layout\Definition\Builder;

use Raw\Component\Admin\Layout\Definition\TemplateNode;

class TemplateNodeDefinition extends ArrayNodeDefinition
{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->context([]);
    }

    /**
     * @param string $template
     * @return TemplateNodeDefinition
     */
    public function template($template)
    {
        return $this->attribute('template', $template);
    }

    /**
     * @param array $context
     * @return TemplateNodeDefinition
     */
    public function context(array $context)
    {
        return $this->attribute('context', $context);
    }

    protected function instantiateLayout()
    {
        return new TemplateNode($this->name, $this->parent);
    }
}