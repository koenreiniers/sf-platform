<?php
namespace Raw\Bundle\MenuBundle\Twig;

use Knp\Menu\Loader\ArrayLoader;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\VarDumper\VarDumper;

class MenuExtension extends \Twig_Extension implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private $helper;

    /**
     * MenuExtension constructor.
     * @param $helper
     */
    public function __construct($helper)
    {
        $this->helper = $helper;
    }


    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('raw_menu_render', [$this, 'renderMenu'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function renderMenu($menu, array $options = [])
    {
        if(is_array($menu)) {
            $factory = $this->container->get('knp_menu.factory');
            $loader = new ArrayLoader($factory);
            $menu = $loader->load($menu);
        }
        $content = $this->helper->render($menu, $options);
        return $content;
    }
}