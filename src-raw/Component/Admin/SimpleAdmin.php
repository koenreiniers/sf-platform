<?php
namespace Raw\Component\Admin;

use Raw\Component\Admin\Layout\Definition\Builder\ArrayNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\FormNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleAdmin extends Admin
{
    protected function configure()
    {

    }

    public function getParent()
    {
        return BaseAdmin::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['formType']);
    }


    public function create(FormNodeDefinition $content, ArrayNodeDefinition $actions, $entity, array $options)
    {
        $content
            ->formType($options['formType'])
            ->children()
            ->templateNode()->template('RawAdminBundle:Admin:simple_form.html.twig')->end()
            ->end();
    }

    public function edit(FormNodeDefinition $content, ArrayNodeDefinition $actions, $entity, array $options)
    {
        $content
            ->formType($options['formType'])
            ->children()
                ->templateNode()->template('RawAdminBundle:Admin:simple_form.html.twig')->end()
            ->end();
    }
}