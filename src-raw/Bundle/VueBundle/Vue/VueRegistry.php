<?php
namespace Raw\Bundle\VueBundle\Vue;

class VueRegistry
{
    /**
     * @var array
     */
    private $configs = [];

    /**
     * VueRegistry constructor.
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $this->configs = $configs;
    }

    private function isBundlePath($path)
    {
        return substr($path, 0, 1) === '@';
    }

    private function isAbsolute($path)
    {
        return substr($path, 0, 1) === '/';
    }

    private function resolvePath($path)
    {
        if($this->isAbsolute($path)) {
            return $path;
        } else if($this->isBundlePath($path)) {
            global $kernel;
            return $kernel->locateResource($path);
        } else {
            return $path;
        }
    }

    public function getApp($name)
    {

        $vueApp = new VueApp($name);
        $config = $this->configs[$name];
        foreach($config['templates'] as $templateName => $templatePath) {
            $templatePath = $this->resolvePath($templatePath);
            $templateName = 'templates/'.$templateName;
            $vueApp->setTemplatePath($templateName, $templatePath);
        }
        return $vueApp;
    }
}