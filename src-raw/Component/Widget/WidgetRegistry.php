<?php
namespace Raw\Component\Widget;


class WidgetRegistry
{
    /**
     * @var WidgetType[]
     */
    private $types;

    /**
     * WidgetRegistry constructor.
     * @param \Raw\Bundle\DashboardBundle\Form\Type\WidgetType[] $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    public function getType($name)
    {
        return $this->types[$name];
    }
}