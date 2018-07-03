<?php
namespace Raw\Component\Sass;

use Raw\Component\Sass\Exception\UndefinedAppException;
use Raw\Component\Sass\Exception\UndefinedVariableException;

class Sass
{
    /**
     * @var SassApp[]
     */
    private $apps;

    /**
     * Sass constructor.
     * @param SassApp[] $apps
     */
    public function __construct($apps)
    {
        $this->apps = [];
        foreach($apps as $app) {
            $this->apps[$app->getName()] = $app;
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasApp($name)
    {
        return isset($this->apps[$name]);
    }

    /**
     * @param string $name
     * @return SassApp
     * @throws UndefinedAppException
     */
    public function getApp($name)
    {
        if(!$this->hasApp($name)) {
            throw new UndefinedAppException($name, $this->apps);
        }
        return $this->apps[$name];
    }
}