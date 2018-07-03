<?php
namespace Raw\Pager\Renderer\Engine;

use Raw\Pager\PagerView;

interface EngineInterface
{
    /**
     * @param PagerView $pager
     * @param array $options
     * @return string
     */
    public function render(PagerView $pager, array $options = []);
}