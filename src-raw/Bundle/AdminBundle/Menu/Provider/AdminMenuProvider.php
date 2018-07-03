<?php
namespace Raw\Bundle\AdminBundle\Menu\Provider;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Loader\ArrayLoader;
use Knp\Menu\Provider\MenuProviderInterface;
use Raw\Component\Admin\AdminRegistry;
use Raw\Component\Admin\ResolvedAdmin;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class AdminMenuProvider implements MenuProviderInterface
{
    use ContainerAwareTrait;

    /**
     * @var AdminRegistry
     */
    private $adminRegistry;

    /**
     * @var string
     */
    private $prefix = 'admin_';

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * AdminMenuProvider constructor.
     * @param AdminRegistry $adminRegistry
     */
    public function __construct(AdminRegistry $adminRegistry, FactoryInterface $factory)
    {
        $this->adminRegistry = $adminRegistry;
        $this->factory = $factory;
    }

    protected function createSidebarMenu(ResolvedAdmin $admin)
    {
        $config = [
            'label' => ucfirst($admin->getOption('plural')),
            'uri' => '#',
            'children' => [
                'list' => [
                    'label' => 'List of '.$admin->getOption('plural'),
                    'route' => $admin->getRoute('list'),
                    'extras' => [
                        'routes' => [
                            $admin->getRoute('list'),
                            $admin->getRoute('edit'),
                            $admin->getRoute('delete'),
                            $admin->getRoute('view'),
                        ],
                    ],
                ],
                'create' => [
                    'label' => 'Create new '.$admin->getOption('singular'),
                    'route' => $admin->getRoute('create'),
                ],
            ],

        ];
        $loader = new ArrayLoader($this->factory);
        return $loader->load($config);
    }

    public function get($name, array $options = array())
    {
        $adminAlias = substr($name, strlen($this->prefix));
        $admin = $this->adminRegistry->getAdmin($adminAlias);

        $menu = $this->createSidebarMenu($admin);

        return $menu;
    }
    public function has($name, array $options = array())
    {
        if(strpos($name, $this->prefix) !== 0) {
            return false;
        }
        $adminAlias = substr($name, strlen($this->prefix));
        return $this->adminRegistry->hasAdmin($adminAlias);
    }
}