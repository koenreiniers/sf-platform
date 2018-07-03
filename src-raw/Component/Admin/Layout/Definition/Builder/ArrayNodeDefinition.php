<?php
namespace Raw\Component\Admin\Layout\Definition\Builder;

use Raw\Component\Admin\Layout\Definition\ActionNode;
use Raw\Component\Admin\Layout\Definition\ArrayNode;

class ArrayNodeDefinition extends NodeDefinition
{
    /**
     * @var NodeBuilder
     */
    protected $nodeBuilder;

    public function setBuilder(NodeBuilder $nodeBuilder)
    {
        $this->nodeBuilder = $nodeBuilder;
    }

    protected function instantiateLayout()
    {
        return new ArrayNode($this->name, $this->parent);
    }

    public function append(NodeDefinition $child)
    {
        $this->children[$child->getName()] = $child;
        $child->setParent($this);
        return $this;
    }

    private function getNodeBuilder()
    {
        if($this->nodeBuilder === null) {
            $this->nodeBuilder = new NodeBuilder();
        }
        return $this->nodeBuilder->setParent($this);
    }

    public function createLayout()
    {
        /** @var ArrayNode $node */
        $node = parent::createLayout();
        foreach($this->children as $child) {
            $node->addChild($child->createLayout());
        }
        return $node;
    }

    /**
     * @return NodeBuilder
     */
    public function children()
    {
        return $this->getNodeBuilder();
    }
}