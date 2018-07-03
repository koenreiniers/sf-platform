<?php
namespace Raw\Component\OAuth2\Grant\Exception;

class GrantMissingParametersException extends GrantException
{
    public function __construct(array $parameters, \Exception $previous = null)
    {
        $message = sprintf('Missing required parameters: %s', implode(',', $parameters));
        $code = 200;
        parent::__construct($message, $code, $previous);
    }
}