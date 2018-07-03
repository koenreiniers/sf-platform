<?php
namespace Raw\Component\OAuth2\Grant\Exception;

class GrantTypeNotFoundException extends GrantException
{
    public function __construct($name, \Exception $previous = null)
    {
        $message = sprintf('Grant type "%s" does not exist', $name);
        $code = 100;
        parent::__construct($message, $code, $previous);
    }
}