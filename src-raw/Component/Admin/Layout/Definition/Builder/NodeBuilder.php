<?php
namespace Raw\Component\Admin\Layout\Definition\Builder;

class NodeBuilder
{
    /**
     * @var NodeDefinition
     */
    protected $parent;

    /**
     * @param string $name
     * @return TabsNodeDefinition
     */
    public function tabsNode($name = null)
    {
        return $this->node($name, TabsNodeDefinition::class);
    }

    /**
     * @param string $name
     * @return TabNodeDefinition
     */
    public function tabNode($name = null)
    {
        return $this->node($name, TabNodeDefinition::class);
    }

    /**
     * @param string $name
     * @return FormNodeDefinition
     */
    public function formNode($name)
    {
        return $this->node($name, FormNodeDefinition::class);
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @param string $name
     * @return GridNodeDefinition
     */
    public function gridNode($name)
    {
        return $this->node($name, GridNodeDefinition::class);
    }

    /**
     * @return NodeBuilder
     */
    public function end()
    {
        return $this->parent;
    }

    /**
     * @param string $name
     * @return FormRowNodeDefinition
     */
    public function formRowNode($name)
    {
        return $this->node($name, FormRowNodeDefinition::class);
    }

    /**
     * @param string $name
     * @return TemplateNodeDefinition
     */
    public function templateNode($name = null)
    {
        return $this->node($name, TemplateNodeDefinition::class);
    }

    /**
     * @param string $name
     * @return ArrayNodeDefinition
     */
    public function arrayNode($name)
    {
        return $this->node($name, ArrayNodeDefinition::class);
    }

    /**
     * @param string $name
     * @return ActionNodeDefinition
     */
    public function actionNode($name)
    {
        return $this->node($name, ActionNodeDefinition::class);
    }

    public function node($name, $type)
    {
        $className = $type;

        $node = new $className($name);

        $this->append($node);

        return $node;
    }

    public function append(NodeDefinition $node)
    {
        if ($node instanceof ArrayNodeDefinition) {
            $builder = clone $this;
            $builder->setParent(null);
            $node->setBuilder($builder);
        }

        if($this->parent !== null) {
            $this->parent->append($node);
            $node->setParent($this);
        }
        return $this;
    }
}