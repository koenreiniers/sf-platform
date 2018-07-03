<?php
namespace Raw\Component\Admin\Layout\Definition;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class FormNode extends ArrayNode
{
    public function getFormType()
    {
        return $this->getAttribute('formType');
    }

    public function getFormOptions()
    {
        return $this->getAttribute('formOptions');
    }
}