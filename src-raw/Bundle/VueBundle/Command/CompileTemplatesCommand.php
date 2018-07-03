<?php
namespace Raw\Bundle\VueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\VarDumper\VarDumper;

class CompileTemplatesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('raw:vue:compile-templates');
        $this->addArgument('src');
        $this->addArgument('dst');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $srcDir = $input->getArgument('src');
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
        VarDumper::dump($templates);
    }
}