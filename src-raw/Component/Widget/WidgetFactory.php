<?php
namespace Raw\Component\Widget;

use Raw\Bundle\DashboardBundle\Entity\Widget;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\VarDumper\VarDumper;

class WidgetFactory
{
    /**
     * @var WidgetRegistry
     */
    private $registry;

    /**
     * WidgetFactory constructor.
     * @param WidgetRegistry $registry
     */
    public function __construct(WidgetRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function create($type, array $settings = [])
    {
        if(is_string($type)) {
            $type = $this->registry->getType($type);
        }

        if(!$type instanceof WidgetType) {
            throw new \InvalidArgumentException();
        }
        $widget = new Widget();
        $widget->setType($type->getAlias());
        $widget->setTitle($type->getAlias());

        $resolver = new OptionsResolver();
        $type->configureSettings($resolver);
        $settings = $resolver->resolve($settings);

        $widget->setSettings($settings);

        return $widget;
    }
}