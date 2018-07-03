<?php
namespace Raw\Component\Rest;

use Symfony\Component\HttpFoundation\Response;

interface ResponderInterface
{
    /**
     * @param mixed $payload
     * @return Response
     */
    public function respond($payload);

    /**
     * @param mixed $payload
     * @return bool
     */
    public function supports($payload);
}