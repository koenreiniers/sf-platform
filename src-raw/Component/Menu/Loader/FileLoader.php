<?php
namespace Raw\Component\Menu\Loader;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Loader\ArrayLoader;
use Knp\Menu\Loader\LoaderInterface;
use Symfony\Component\Yaml\Yaml;

abstract class FileLoader implements LoaderInterface
{
    protected $validExtensions = ['yml', 'yaml'];

    public function supports($data)
    {
        return file_exists($data) && in_array(pathinfo($data, PATHINFO_EXTENSION), $this->validExtensions);
    }
}