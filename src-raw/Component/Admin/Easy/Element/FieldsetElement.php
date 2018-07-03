<?php
namespace Raw\Component\Admin\Easy\Element;

class FieldsetElement extends LayoutElement
{
    /**
     * @return LayoutElement
     */
    public function endFieldset()
    {
        return $this->end();
    }
}