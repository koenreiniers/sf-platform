<?php
namespace Raw\Component\Admin\Easy\Element;

use Raw\Component\Admin\Easy\Element;
use Raw\Component\Admin\Layout\Definition\Builder\ArrayNodeDefinition;

class LayoutElement extends Element
{

    public function setTitle($title)
    {
        return $this->setAttribute('title', $title);
    }

    public function getTitle()
    {
        return $this->getAttribute('title');
    }


    public function addGrid($name, array $parameters = [])
    {
        return $this->add(GridElement::class, [
            'name' => $name,
            'gridName' => $name,
            'gridParameters' => $parameters,
        ]);
    }


    /**
     * @return TabsetElement
     */
    public function addTabset()
    {
        return $this->add(TabsetElement::class, [

        ]);
    }

    /**
     * @return FieldsetElement
     */
    public function addFieldset($name)
    {
        return $this->add(FieldsetElement::class, [
            'name' => $name,
            'title' => $name,
        ]);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function addField($name)
    {
        return $this->add(FieldElement::class, [
            'name' => $name,
        ]);
    }

    /**
     * @param string $name
     * @param array $context
     * @return $this
     */
    public function template($name, array $context = [])
    {
        return $this->add(TemplateElement::class, [
            'name' => $name,
            'context' => $context,
        ]);
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return $this
     */
    public function controller($name, array $parameters = [])
    {
        return $this->add(ControllerElement::class, [
            'name' => $name,
            'parameters' => $parameters,
        ]);
    }



}