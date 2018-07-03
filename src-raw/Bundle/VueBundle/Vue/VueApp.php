<?php
namespace Raw\Bundle\VueBundle\Vue;

class VueApp
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $templatePaths = [];

    /**
     * VueApp constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setTemplatePath($name, $path)
    {
        $this->templatePaths[$name] = $path;
        return $this;
    }

    /**
     * @return array
     */
    public function getTemplatePaths()
    {
        return $this->templatePaths;
    }
}