<?php
namespace Raw\Component\OAuth2\Grant\Exception;

class CredentialsExpiredException extends GrantException
{
    /**
     * CredentialsExpiredException constructor.
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct($message = 'The provided credentials have expired', \Exception $previous = null)
    {
        $code = 310;
        parent::__construct($message, $code, $previous);
    }
}