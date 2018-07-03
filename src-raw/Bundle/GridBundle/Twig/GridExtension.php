<?php
namespace Raw\Bundle\GridBundle\Twig;

use Raw\Bundle\DataGridBundle\Controller\GridController;
use Raw\Component\Grid\GridFactory;
use Raw\DataGrid\DataGrid;
use Raw\Component\Grid\GridRegistry;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\VarDumper\VarDumper;

class GridExtension extends \Twig_Extension
{
    use ContainerAwareTrait;

    /**
     * @var GridFactory
     */
    private $gridRegistry;

    private function initDeps()
    {
        $this->gridRegistry = $this->container->get('raw_grid.factory');
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('raw_grid_config', [$this, 'getGridConfig'], [

            ]),
        ];
    }

    public function getGridConfig($gridName)
    {
        $this->initDeps();

        $grid = $this->gridRegistry->getGrid($gridName);
        $config = $grid->createView()->vars;

        return $config;
    }


}