<?php
namespace Raw\Component\Bol\Plaza\Http\Authentication;

use Psr\Http\Message\RequestInterface;

interface AuthenticationProviderInterface
{
    /**
     * Adds authentication credentials to the request. Returns a new request instance.
     *
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function authenticate(RequestInterface $request);
}