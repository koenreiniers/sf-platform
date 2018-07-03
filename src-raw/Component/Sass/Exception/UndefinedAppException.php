<?php
namespace Raw\Component\Sass\Exception;

class UndefinedAppException extends SassException
{
    public function __construct($app, array $definedApps, $code = 0, \Exception $previous = null)
    {
        $message = sprintf('App "%s" is not defined', $app);
        parent::__construct($message, $code, $previous);
    }
}