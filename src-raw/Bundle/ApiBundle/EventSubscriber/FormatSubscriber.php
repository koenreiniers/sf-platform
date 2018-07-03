<?php
namespace Raw\Bundle\ApiBundle\EventSubscriber;

use Raw\Bundle\ApiBundle\Envelope\CollectionEnvelope;
use Raw\Bundle\ApiBundle\Envelope\Envelope;
use Raw\Component\Rest\RequestDecoder;
use Raw\Component\Rest\ResponderInterface;
use Raw\Component\Rest\RestEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\VarDumper\VarDumper;

class FormatSubscriber implements EventSubscriberInterface
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var RequestDecoder
     */
    private $requestDecoder;

    /**
     * @var ResponderInterface
     */
    private $responder;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * FormatSubscriber constructor.
     * @param Serializer $serializer
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(Serializer $serializer, EventDispatcherInterface $eventDispatcher)
    {
        $this->serializer = $serializer;
        $this->requestDecoder = new RequestDecoder($serializer);
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::VIEW => 'onKernelView',
            KernelEvents::RESPONSE => 'onKernelResponse',
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    protected function isApiRequest(Request $request)
    {
        return $request->attributes->get('_api') !== null;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if(!$this->isApiRequest($request)) {
            return;
        }
        $this->requestDecoder->decode($request);

        $this->eventDispatcher->dispatch(RestEvents::REQUEST, $event);
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $request = $event->getRequest();
        if(!$this->isApiRequest($request)) {
            return;
        }
        $this->eventDispatcher->dispatch(RestEvents::EXCEPTION, $event);
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if(!$this->isApiRequest($event->getRequest())) {
            return;
        }

        $response = $event->getResponse();

        if($response instanceof RedirectResponse) {
            $response = $this->createResponse($event->getRequest());
            $event->setResponse($response);
        }

        $this->eventDispatcher->dispatch(RestEvents::RESPONSE, $event);
    }

    private function getBodyData(Envelope $envelope)
    {
        if($envelope instanceof CollectionEnvelope) {

            return $envelope->getItems();
        }
        return $envelope->getData();
    }

    private function getHeaders(Envelope $envelope)
    {
        if($envelope instanceof CollectionEnvelope) {
            return [
                'X-Count' => $envelope->getCount(),
            ];
        }

        return $envelope->getHeaders();
    }

    private function createResponse(Request $request, $envelope = null)
    {



        if(!$envelope instanceof Envelope) {
            $envelope = new Envelope($envelope);
        }

        $extraHeaders = $this->getHeaders($envelope);
        $contentType = $request->getContentType() ?: 'application/json';

        $formatMap = [
            'application/json' => 'json',
            'application/xml' => 'xml',
        ];

        $format = isset($formatMap[$contentType]) ? $formatMap[$contentType] : 'json';



        $response = new Response();
        $response->headers->set('Content-Type', $contentType);

        if(!$request->isMethod('HEAD')) {
            $bodyData = $this->getBodyData($envelope);

            $content = $this->serializer->encode($bodyData, $format);
            $response->setContent($content);
        }


        foreach($extraHeaders as $key => $val) {
            $response->headers->set($key, $val);
        }

        return $response;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {

        $request = $event->getRequest();

        if(!$this->isApiRequest($request)) {
            return;
        }

        $data = $event->getControllerResult();

        $response = $this->createResponse($request, $data);

        $event->setResponse($response);
    }
}