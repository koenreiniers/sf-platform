<?php
namespace Raw\Component\Sass;

use Raw\Component\Sass\Exception\UndefinedVariableException;

class SassApp
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private $imports;

    /**
     * @var array
     */
    private $variables;

    /**
     * Sass constructor.
     * @param string
     * @param string[] $imports
     * @param array $variables
     */
    public function __construct($name, array $imports, array $variables)
    {
        $this->name = $name;
        $this->imports = $imports;
        $this->variables = $variables;
    }

    /**
     * @return \string[]
     */
    public function getImports()
    {
        return $this->imports;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasVariable($name)
    {
        return isset($this->variables[$name]);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws UndefinedVariableException
     */
    public function getVariable($name)
    {
        if(!$this->hasVariable($name)) {
            throw new UndefinedVariableException($name, $this->variables);
        }
        return $this->variables[$name];
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }
}