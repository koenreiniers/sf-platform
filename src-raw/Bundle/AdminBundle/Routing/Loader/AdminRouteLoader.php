<?php
namespace Raw\Bundle\AdminBundle\Routing\Loader;

use Raw\Component\Admin\AdminRegistry;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\VarDumper\VarDumper;

class AdminRouteLoader extends Loader
{
    /**
     * @var AdminRegistry
     */
    private $adminRegistry;

    /**
     * AdminRouteLoader constructor.
     * @param AdminRegistry $adminRegistry
     */
    public function __construct(AdminRegistry $adminRegistry)
    {
        $this->adminRegistry = $adminRegistry;
    }

    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();

        $admins = $this->adminRegistry->getAdmins();

        foreach($admins as $admin) {

            $alias = $admin->getAlias();


            $routes = [
                'list' => [
                    'path' => '',
                ],
                'create' => [
                    'path' => '/create',
                ],
                'view' => [
                    'path' => '/{id}',
                ],
                'edit' => [
                    'path' => '/{id}/edit',
                ],
                'delete' => [
                    'path' => '/{id}/delete',
                ],
            ];

            foreach($routes as $verb => $routeOptions) {
                $routeName = 'crud.'.$alias.'.'.$verb;

                $route = new Route('/'.$alias.$routeOptions['path'], [
                    '_controller' => 'RawAdminBundle:Admin:'.$verb,
                    'alias' => $alias,
                ]);

                $collection->add($routeName, $route);
            }

            foreach($admin->getActions() as $action) {
                if($action->getName() !== 'history') {
                    continue;
                }

                $routeOptions = [
                    'path' => '/{id}/'.$action->getName(),
                ];

                $routeName = 'crud.'.$alias.'.'.$action->getName();

                $route = new Route('/'.$alias.$routeOptions['path'], [
                    '_controller' => 'RawAdminBundle:Admin:page',
                    'verb' => $action->getName(),
                    'alias' => $alias,
                ]);

                $collection->add($routeName, $route);
            }





        }

        return $collection;

        // TODO: Implement load() method.
    }

    public function supports($resource, $type = null)
    {
        return $type === 'raw_admin';
    }
}