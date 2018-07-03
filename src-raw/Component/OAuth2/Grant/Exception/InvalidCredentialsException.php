<?php
namespace Raw\Component\OAuth2\Grant\Exception;

class InvalidCredentialsException extends GrantException
{
    /**
     * InvalidCredentialsException constructor.
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct($message = 'The provided credentials are invalid', \Exception $previous = null)
    {
        $code = 300;
        parent::__construct($message, $code, $previous);
    }
}