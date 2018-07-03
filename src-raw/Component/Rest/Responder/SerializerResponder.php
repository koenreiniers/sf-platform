<?php
namespace Raw\Component\Rest\Responder;

use Raw\Component\Rest\ResponderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;

class SerializerResponder implements ResponderInterface
{

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var Serializer
     */
    private $serializer;

    public function respond($payload)
    {
        $request = $this->requestStack->getCurrentRequest();

        $contentType = $request->getContentType() ?: 'application/json';

        $formatMap = [
            'application/json' => 'json',
            'application/xml' => 'xml',
        ];

        $format = isset($formatMap[$contentType]) ? $formatMap[$contentType] : 'json';

        $content = $this->serializer->encode($payload, $format);
        $response = new Response($content);
        return $response;
    }

    public function supports($payload)
    {
        return is_scalar($payload);
    }
}