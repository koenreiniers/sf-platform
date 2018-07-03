<?php
namespace Raw\Component\Layout;

use Raw\Component\Admin\Layout\Definition\Node;

interface LayoutRendererInterface
{
    public function render(Node $node, array $context = [], array $opts = []);
}