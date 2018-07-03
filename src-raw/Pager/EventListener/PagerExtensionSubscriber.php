<?php
namespace Raw\Pager\EventListener;

use Raw\Pager\Event\BuildPagerEvent;
use Raw\Pager\Event\BuildViewEvent;
use Raw\Pager\Event\HandleRequestEvent;
use Raw\Pager\PagerEvents;
use Raw\Pager\PagerExtension;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PagerExtensionSubscriber implements EventSubscriberInterface
{
    /**
     * @var PagerExtension
     */
    private $extension;

    /**
     * PagerExtensionSubscriber constructor.
     * @param PagerExtension $extension
     */
    public function __construct(PagerExtension $extension)
    {
        $this->extension = $extension;
    }

    public static function getSubscribedEvents()
    {
        return [
            PagerEvents::BUILD => 'onBuild',
            PagerEvents::BUILD_VIEW => 'onBuildView',
            PagerEvents::HANDLE_REQUEST => 'onHandleRequest',
        ];
    }

    public function onBuild(BuildPagerEvent $event)
    {
        $builder = $event->getBuilder();
        $this->extension->buildPager($builder, $builder->getOptions());
    }

    public function onBuildView(BuildViewEvent $event)
    {
        $view = $event->getView();
        $pager = $event->getPager();
        $this->extension->buildView($view, $pager, $pager->getConfig()->getOptions());
    }

    public function onHandleRequest(HandleRequestEvent $event)
    {
        $pager = $event->getPager();
        $request = $event->getRequest();
        $this->extension->handleRequest($pager, $request, $pager->getConfig()->getOptions());
    }
}