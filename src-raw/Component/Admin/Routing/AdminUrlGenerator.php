<?php
namespace Raw\Component\Admin\Routing;

use Raw\Component\Admin\ResolvedAdmin;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;

class AdminUrlGenerator implements UrlGeneratorInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $adminAlias;

    /**
     * @var array
     */
    private $routeMap;

    /**
     * AdminUrlGenerator constructor.
     * @param RouterInterface $router
     * @param string $adminAlias
     * @param array $routeMap
     */
    public function __construct(RouterInterface $router, $adminAlias, array $routeMap)
    {
        $this->router = $router;
        $this->adminAlias = $adminAlias;
        $this->routeMap = $routeMap;
    }

    public function resolveRoute($name)
    {
        return isset($this->routeMap[$name]) ? $this->routeMap[$name] : $name;
    }

    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        if($parameters === null) {
            $parameters = [];
        } else if(is_object($parameters)) {
            $parameters = [
                'id' => $parameters->getId(),
            ];
        }
        if($name === 'list') {
            $parameters = [];
        }

        $parameters['alias'] = $this->adminAlias;

        $name = $this->resolveRoute($name);
        return $this->router->generate($name, $parameters, $referenceType);
    }


    public function getContext()
    {
        return $this->router->getContext();
    }

    public function setContext(RequestContext $context)
    {
        $this->router->setContext($context);
    }
}