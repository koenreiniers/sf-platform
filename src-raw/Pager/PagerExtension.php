<?php
namespace Raw\Pager;

use Raw\Pager\Pager;
use Raw\Pager\PagerView;

abstract class PagerExtension
{
    public function buildPager(PagerBuilder $builder, array $options)
    {

    }

    public function handleRequest(Pager $pager, $request, array $options)
    {

    }

    public function buildView(PagerView $view, Pager $pager, array $options)
    {

    }
}