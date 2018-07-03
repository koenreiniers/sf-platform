<?php
namespace Raw\Component\Admin\Easy;


use Raw\Component\Admin\Layout\Definition\Builder\NodeDefinition;

interface ElementInterface
{
    /**
     * @return ElementInterface
     */
//    protected function end();

    /**
     * @return ElementInterface
     */
    public function getParent();

    /**
     * @return ElementInterface[]
     */
    public function getChildren();

    /**
     * @param NodeDefinition|null $existingNode
     * @return NodeDefinition
     */
    public function toNode(NodeDefinition $existingNode = null);

    public function getDataValue($name, $default = null);

    public function setDataValue($name, $value);

    public function getData();

    public function setData(array $data);
}