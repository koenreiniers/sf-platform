<?php
namespace Raw\Component\Admin;

use Raw\Component\Admin\Easy\Element\FormElement;
use Raw\Component\Admin\Easy\Element\GridElement;
use Raw\Component\Admin\Easy\Element\LayoutElement;

interface AdminInterface
{
    /**
     * @param LayoutElement $layout
     * @param object $entity
     * @param array $options
     */
    public function buildViewLayout(LayoutElement $layout, $entity, array $options);

    /**
     * @param LayoutElement $layout
     * @param array $options
     */
    public function buildListLayout(LayoutElement $layout, array $options);

    /**
     * @param FormElement $layout
     * @param object $entity
     * @param array $options
     */
    public function buildEditLayout(FormElement $layout, $entity, array $options);

    /**
     * @param FormElement $layout
     * @param object $entity
     * @param array $options
     */
    public function buildCreateLayout(FormElement $layout, $entity, array $options);

    /**
     * @param LayoutElement $layout
     * @param object $entity
     * @param array $options
     */
    public function buildDeleteLayout(LayoutElement $layout, $entity, array $options);
}