<?php
namespace Raw\Component\Admin;

use Raw\Component\Admin\Easy\Actions;
use Raw\Component\Admin\Easy\Element\FormElement;
use Raw\Component\Admin\Easy\Element\GridElement;
use Raw\Component\Admin\Easy\Element\LayoutElement;
use Raw\Component\Admin\Layout\Definition\Builder\ArrayNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\ContentNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\FormNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class EasyAdmin extends Admin
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

    public function view(ContentNodeDefinition $content, ArrayNodeDefinition $actions, $entity, array $options)
    {
        $layout = new LayoutElement();

        $easyActions = new Actions();
        $this->easyView($layout, $easyActions, $entity);

        foreach($easyActions->all() as $actionName) {
            $actions
                ->children()
                    ->actionNode($actionName)->end();
        }

        $layout->toNode($content);
    }

    /**
     * @param LayoutElement $layout
     * @param Actions $actions
     * @param object $entity
     * @return void
     */
    abstract protected function easyView(LayoutElement $layout, Actions $actions, $entity);

    /**
     * @param FormElement $form
     * @return void
     */
    abstract protected function easyForm(FormElement $form);


    /**
     * @param GridElement $grid
     * @return void
     */
    abstract protected function easyIndex(GridElement $grid);

    /**
     * @param FormElement $form
     * @return void
     */
    protected function easyEdit(FormElement $form)
    {
        $this->easyForm($form);
    }

    /**
     * @param FormElement $form
     * @return void
     */
    protected function easyCreate(FormElement $form)
    {
        $this->easyForm($form);
    }

    public function index(ContentNodeDefinition $content, ArrayNodeDefinition $actions, array $options)
    {

        $grid = $content
            ->children()
                ->gridNode('grid');

        $layout = new GridElement();

        $this->easyIndex($layout);

        $layout->toNode($grid);
    }

    public function create(FormNodeDefinition $content, ArrayNodeDefinition $actions, $entity, array $options)
    {
        $layout = new FormElement();

        $this->easyCreate($layout);

        $layout->toNode($content);
    }

    public function edit(FormNodeDefinition $content, ArrayNodeDefinition $actions, $entity, array $options)
    {
        $layout = new FormElement();

        $this->easyEdit($layout);

        $layout->toNode($content);
    }
}