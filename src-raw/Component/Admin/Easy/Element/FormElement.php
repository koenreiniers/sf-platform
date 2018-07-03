<?php
namespace Raw\Component\Admin\Easy\Element;

use Raw\Component\Admin\Easy\Element;
use Raw\Component\Admin\Easy\ElementInterface;
use Raw\Component\Admin\Layout\Definition\Builder\FormNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\NodeDefinition;

class FormElement extends LayoutElement
{
    public function __construct(ElementInterface $parent = null, array $data = [])
    {
        parent::__construct($parent, $data);
        $this->setData([
            'formOptions' => [],
        ]);
    }

    /**
     * @param string $formType
     * @return $this
     */
    public function setFormType($formType)
    {
        $this->setDataValue('formType', $formType);
        return $this;
    }

    public function setFormOptions(array $formOptions)
    {
        $this->setDataValue('formOptions', $formOptions);
        return $this;
    }
}