<?php
namespace Raw\Component\Rest;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

class RequestDecoder
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * RequestDecoder constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function decode(Request $request)
    {
        $data = $this->decodeRequestData($request);

        if(is_array($data)) {
            $request->request->replace($data);
        }
    }

    private function decodeRequestData(Request $request)
    {
        $content = $request->getContent();
        if(empty($content)) {
            return null;
        }
        switch($request->getContentType()) {
            case 'json':
                return $this->serializer->decode($content, 'json');
            case 'xml':
                return $this->serializer->decode($content, 'xml');
        }

        throw new \Exception(sprintf('Content type "%s" is not supported', $request->getContentType()));
    }
}