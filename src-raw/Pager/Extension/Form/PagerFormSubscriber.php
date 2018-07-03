<?php
namespace Raw\Pager\Extension\Form;

use Raw\Pager\Adapter\EmptyAdapter;
use Raw\Pager\Adapter\InMemoryAdapter;
use Raw\Pager\PagerFactory;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\VarDumper\VarDumper;

class PagerFormSubscriber implements EventSubscriberInterface
{
    use ContainerAwareTrait;

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    /**
     * @var PagerFactory
     */
    private $pagerFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    public function setContainer(ContainerInterface $container)
    {
        $this->pagerFactory = $container->get('raw_pager.factory');
        $this->router = $container->get('router');
    }


    public function onKernelRequest(GetResponseEvent $event)
    {


        $request = $event->getRequest();


        if(!$request->isMethod('POST')) {
            return;
        }

        $metaFormkey = '_raw_pager_form_meta';

        if(!$request->get($metaFormkey)) {
            return;
        }

        $data = $request->get($metaFormkey);

        $ns = $data['namespace'];

        if($ns === null) {
            return;
        }

        $totalCount = $data['totalCount'];
        $items = array_fill(0, $totalCount, []);

        $builder = $this->pagerFactory->createBuilder(new InMemoryAdapter($items));

        $builder->setNamespace($ns);

        $pager = $builder->getPager();

        $pager->handleRequest($request);


        if($request->isXmlHttpRequest()) {
            $response = new JsonResponse();
            $event->setResponse($response);
            return;
        }


        $route = $request->attributes->get('_route');
        $routeParams = $request->attributes->get('_route_params');

        if(isset($routeParams['page'])) {
            $routeParams['page'] = $pager->getCurrentPage();
        }

        $redirectUrl = $this->router->generate($route, $routeParams);

        $response = new RedirectResponse($redirectUrl);

        $event->setResponse($response);

    }


}