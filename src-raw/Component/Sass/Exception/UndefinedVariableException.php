<?php
namespace Raw\Component\Sass\Exception;

class UndefinedVariableException extends SassException
{
    public function __construct($variable, array $definedVariables, $code = 0, \Exception $previous = null)
    {
        $message = sprintf('Variable "%s" is not defined', $variable);
        parent::__construct($message, $code, $previous);
    }
}