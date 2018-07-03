<?php
namespace Raw\Bundle\MenuBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\VarDumper\VarDumper;

class RegisterMenuPathsPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @param $configDirectory
     * @return SplFileInfo[]
     */
    protected function getFilesIn(ContainerBuilder $container, $configDirectory)
    {
        $bundles = $container->getParameter('kernel.bundles');

        $files = [];

        foreach($bundles as $bundleName => $bundleClass) {

            $rc = new \ReflectionClass($bundleClass);

            $bundleRoot = dirname($rc->getFileName());

            $configRoot = $bundleRoot.'/Resources/config';

            $dir = $configRoot.'/'.$configDirectory;

            if(!is_dir($dir)) {
                continue;
            }

            foreach(Finder::create()->in($dir) as $fileInfo) {
                /** @var SplFileInfo $fileInfo */
                $files[] = $fileInfo;
            }


        }
        return $files;
    }

    public function process(ContainerBuilder $container)
    {
        $files = $this->getFilesIn($container, 'menu');

        $pathsByName = [];

        foreach($files as $file) {
            $menuName = pathinfo($file->getRelativePathname(), PATHINFO_FILENAME);
            $path = $file->getRealPath();

            if (!isset($pathsByName[$menuName])) {
                $pathsByName[$menuName] = [];
            }

            $pathsByName[$menuName][] = $path;
        }
        $container->findDefinition('raw_menu.provider.yaml_file')->replaceArgument(0, $pathsByName);
    }
}