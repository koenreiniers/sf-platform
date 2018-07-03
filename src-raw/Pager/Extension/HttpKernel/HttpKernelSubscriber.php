<?php
namespace Raw\Pager\Extension\HttpKernel;

use Raw\Pager\Pager;
use Raw\Pager\PagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class HttpKernelSubscriber implements EventSubscriberInterface
{

    /**
     * @var PagerRegistry
     */
    private $pagerRegistry;

    /**
     * HttpKernelSubscriber constructor.
     * @param PagerRegistry $pagerRegistry
     */
    public function __construct(PagerRegistry $pagerRegistry)
    {
        $this->pagerRegistry = $pagerRegistry;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        foreach($this->pagerRegistry->getPagers() as $pager) {
            $pager->terminateRequest($request);
        }
    }
}