<?php
namespace Raw\Component\Admin\Layout\Definition\Builder;

use Raw\Component\Admin\Layout\Definition\FormNode;

class FormNodeDefinition extends ArrayNodeDefinition
{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->formOptions([]);
        $this->title($name);
    }

    /**
     * @param $title
     * @return $this
     */
    public function title($title)
    {
        return $this->attribute('title', $title);
    }

    public function formType($formType)
    {
        return $this->attribute('formType', $formType);
    }

    public function formOptions(array $formOptions)
    {
        return $this->attribute('formOptions', $formOptions);
    }

    protected function instantiateLayout()
    {
        return new FormNode($this->name, $this->parent);
    }



}