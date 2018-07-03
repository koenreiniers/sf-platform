<?php
namespace Raw\Component\Admin\Easy;

use Raw\Component\Admin\Layout\Definition\Builder\NodeDefinition;

class Element implements ElementInterface, \IteratorAggregate
{
    /**
     * @var Element
     */
    private $parent;

    /**
     * @var Element[]
     */
    protected $children = [];

    /**
     * @var array
     */
    private $data = [];

    public function getIterator()
    {
        return new \ArrayIterator($this->children);
    }

    public function getAttribute($name)
    {
        return $this->getDataValue($name);
    }

    /**
     * Element constructor.
     * @param ElementInterface $parent
     */
    public function __construct(ElementInterface $parent = null, array $data = [])
    {
        $this->parent = $parent;
        $this->data = $data;
    }

    public function setAttribute($name, $value)
    {
        return $this->setDataValue($name, $value);
    }

    protected function addElement(ElementInterface $element)
    {
        $this->children[] = $element;
        if($element instanceof AutoEndElementInterface) {
            return $this;
        }
        return $element;
    }

    protected function add($className, array $data = [])
    {
        $element = new $className($this, $data);
        return $this->addElement($element);
    }

    public function getDataValue($name, $default = null)
    {
        return isset($this->data[$name]) ? $this->data[$name] : $default;
    }

    public function setDataValue($name, $value)
    {
        $this->data[$name] = $value;
        return $this;

    }

    public function getData()
    {
        return $this->data;
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return Element
     */
    public function end()
    {
        return $this->parent;
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @inheritdoc
     */
    public function getChildren()
    {
        return $this->children;
    }

    protected function configureNodeAttributes(NodeDefinition $node)
    {

    }

    protected function getNodeClass()
    {
        return NodeDefinition::class;
    }

    /**
     * @inheritdoc
     */
    public function toNode(NodeDefinition $existingNode = null)
    {
        $node = $existingNode;

        if($node === null) {
            $nodeClass = $this->getNodeClass();
            $nodeName = $this->getDataValue('name', 'no-node-name-specified');
            $node = new $nodeClass($nodeName);
        }

        $this->configureNodeAttributes($node);

        $index = 0;
        foreach($this->children as $childElement) {
            if($childElement->getDataValue('name') === null) {
                $childElement->setDataValue('name', $index);
            }
            $childNode = $childElement->toNode();
            $node->append($childNode);
            $index++;
        }

        return $node;
    }


}