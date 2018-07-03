<?php
namespace Raw\Component\Admin;

use Raw\Component\Admin\Easy\Element\FormElement;
use Raw\Component\Admin\Easy\Element\LayoutElement;
use Raw\Component\Admin\Layout\Definition\Builder\ArrayNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\ContentNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\FormNodeDefinition;
use Raw\Component\Layout\Action\ActionCollectionBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class Admin implements AdminInterface
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var array
     */
    protected $routeAliases = [];

    /**
     * @var array
     */
    protected $templateAliases = [];

    public function getRoute($alias)
    {
        return isset($this->routeAliases[$alias]) ? $this->routeAliases[$alias] : $alias;
    }

    public function createEntity()
    {
        return new $this->className;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function configureActions(ActionCollectionBuilder $actions)
    {
    }

    protected function setClassName($className)
    {
        $this->className = $className;
        return $this;
    }

    protected function route($alias, $name)
    {
        $this->routeAliases[$alias] = $name;
        return $this;
    }

    protected function template($alias, $name)
    {
        $this->templateAliases[$alias] = $name;
        return $this;
    }

    public function initialize()
    {
        // TODO..?
        $this->route('create', 'raw_admin.admin.create');
        $this->route('edit', 'raw_admin.admin.edit');
        $this->route('index', 'raw_admin.admin.index');
        $this->route('view', 'raw_admin.admin.view');
        $this->route('delete', 'raw_admin.admin.delete');
        $this->template('create', 'RawAdminBundle:Admin:edit.html.twig');
        $this->template('edit', 'RawAdminBundle:Admin:edit.html.twig');
        $this->template('list', 'RawAdminBundle:Admin:list.html.twig');
        $this->template('view', 'RawAdminBundle:Admin:view.html.twig');
        $this->template('delete', 'RawAdminBundle:Admin:delete.html.twig');
        $this->configure();
    }

    abstract protected function configure();

    public function getParent()
    {
        return BaseAdmin::class;
    }

    public function getTemplate($alias)
    {
        $default = 'RawAdminBundle:Admin:base.html.twig';
        return isset($this->templateAliases[$alias]) ? $this->templateAliases[$alias] : $default;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function buildCreateLayout(FormElement $layout, $entity, array $options)
    {

    }
    public function buildDeleteLayout(LayoutElement $layout, $entity, array $options)
    {

    }
    public function buildEditLayout(FormElement $layout, $entity, array $options)
    {

    }
    public function buildViewLayout(LayoutElement $layout, $entity, array $options)
    {

    }
    public function buildListLayout(LayoutElement $layout, array $options)
    {

    }
}