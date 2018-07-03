<?php
namespace Raw\Component\Admin\Layout\Definition\Builder;

use Raw\Component\Admin\Layout\Definition\ActionNode;
use Raw\Component\Admin\Layout\Definition\ArrayNode;
use Raw\Component\Admin\Layout\Definition\TabsNode;

class TabsNodeDefinition extends NodeDefinition
{
    protected $children = [];

    public function __construct($name)
    {
        parent::__construct($name);
    }

    protected function instantiateLayout()
    {
        return new TabsNode($this->name, $this->parent);
    }

    public function append(NodeDefinition $child)
    {
        if($child->getName() === null) {
            $this->children[] = $child;
        } else {
            $this->children[$child->getName()] = $child;
        }
        $child->setParent($this);
        return $this;
    }

    public function tabNode($name = null)
    {
        $node = new TabNodeDefinition($name);
        $this->append($node);
        return $node;
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
}