<?php
namespace Raw\Component\Admin\Page;

use Raw\Component\Admin\Easy\Element\LayoutElement;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GridAdminPage extends AdminPage
{
    public function buildLayout(LayoutElement $layout, $entity, array $options)
    {
        $layout
            ->addGrid($options['grid_name'], $options['grid_parameters']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['grid_name']);
        $resolver->setDefaults([
            'grid_parameters' => [],
        ]);
    }

}