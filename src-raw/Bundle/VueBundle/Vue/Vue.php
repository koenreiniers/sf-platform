<?php
namespace Raw\Bundle\VueBundle\Vue;

use Symfony\Component\Finder\Finder;

class Vue
{
    public static function generateTemplatePaths($srcDir)
    {
        $srcDir = realpath($srcDir);

        if(!is_dir($srcDir)) {
            throw new \Exception(sprintf('%s is not a valid directory', $srcDir));
        }

        $finder = new Finder();
        $finder->files()->in($srcDir);

        $templates = [];



        foreach($finder as $file) {

            $realPath = $file->getRealPath();

            $templateName = substr($file->getRelativePathName(), 0, -strlen('.html'));

            $templates[$templateName] = $realPath;
        }

        return $templates;
    }
}