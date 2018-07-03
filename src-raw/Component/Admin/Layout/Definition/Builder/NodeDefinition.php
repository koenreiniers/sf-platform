<?php
namespace Raw\Component\Admin\Layout\Definition\Builder;

use Raw\Component\Admin\Layout\Definition\Node;
use Symfony\Component\Form\FormView;

class NodeDefinition
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var LayoutDefinition
     */
    protected $parent;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var NodeDefinition[]
     */
    protected $children = [];

    /**
     * @var NodeBuilder
     */
    protected $nodeBuilder;

    /**
     * Node constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function append(NodeDefinition $child)
    {
        $this->children[$child->getName()] = $child;
        return $this;
    }

    public function getAttribute($name)
    {
        if(!isset($this->attributes[$name])) {
            throw new \Exception(sprintf('Attribute "%s" is not set', $name));
        }
        return $this->attributes[$name];
    }

    protected function newNode($className, $name)
    {
        $node = new $className($name);
        $node->setParent($this);
        return $node;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function attribute($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function attributes(array $attributes)
    {
        foreach($attributes as $name => $value) {
            $this->attribute($name, $value);
        }
        return $this;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }


    /**
     * @return NodeBuilder
     */
    public function end()
    {
        return $this->parent;
    }

    protected function instantiateLayout()
    {
        return new Node($this->name, $this->parent);
    }

    public function createLayout()
    {
        $layout = $this->instantiateLayout();
        $layout->setAttributes($this->attributes);
        return $layout;
    }
}