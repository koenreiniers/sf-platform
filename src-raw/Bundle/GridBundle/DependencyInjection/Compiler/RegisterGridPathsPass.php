<?php
namespace Raw\Bundle\GridBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class RegisterGridPathsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $files = $this->getFilesIn($container, 'grid');

        $pathsByName = [];

        foreach($files as $file) {
            $gridName = pathinfo($file->getRelativePathname(), PATHINFO_FILENAME);
            $path = $file->getRealPath();
            if(!isset($pathsByName[$gridName])) {
                $pathsByName[$gridName] = [];
            }
            $pathsByName[$gridName][] = $path;
            $container->addResource(new FileResource($path));
        }

        $container->findDefinition('raw_grid.mapping.loader.yaml_file')->replaceArgument(0, $pathsByName);
    }

    /**
     * TODO: Move to separate class (BundleConfigReader w/e)
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
}