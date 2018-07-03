<?php
namespace Raw\Filter\Context\Loader;

interface LoaderInterface
{
    public function load($resource, $type = null);
}