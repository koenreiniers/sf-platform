<?php
namespace Raw\Component\Admin;

use Raw\Component\Admin\Routing\AdminUrlGenerator;
use Symfony\Component\Routing\RouterInterface;

class ResolvedAdminFactory
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * ResolvedAdminFactory constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Admin $admin
     * @return ResolvedAdmin
     */
    public function create($alias, Admin $admin, ResolvedAdmin $parent = null)
    {
        $admin->initialize();
        $urlGenerator = $this->createUrlGenerator($alias, $admin);
        $resolvedAdmin = new ResolvedAdmin($alias, $admin, $parent, $urlGenerator);
        return $resolvedAdmin;
    }

    private function createUrlGenerator($alias, Admin $admin)
    {
        $routeMap = $this->getRouteMap($alias, $admin);
        $urlGenerator = new AdminUrlGenerator($this->router, $alias, $routeMap);
        return $urlGenerator;
    }

    private function getRouteMap($alias, Admin $admin)
    {
        $verbs = ['list', 'create', 'edit', 'delete', 'view'];
        $map = [];
        foreach($verbs as $verb) {
            $map[$verb] = 'crud.'.$alias.'.'.$verb;
        }
        return $map;
    }
}