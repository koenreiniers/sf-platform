<?php
namespace Raw\Bundle\SassBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SassCompileCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('raw:sass:compile');
        $this->addArgument('bundle');
        $this->addArgument('input', null, '', 'main');
        $this->addArgument('output');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bundleName = $input->getArgument('bundle');
        $inputFilename = $input->getArgument('input');
        $outputFilename = $input->getArgument('output');
        if(empty($outputFilename)) {
            $outputFilename = $inputFilename;
        }

        $container = $this->getContainer();

        $kernel = $container->get('kernel');

        $publicDir = $kernel->locateResource('@'.$bundleName.'/Resources/public');

        $sassFile = $publicDir.'/scss/'.$inputFilename.'.scss';
        $cssFile = $publicDir.'/css/'.$outputFilename.'.css';

        if(!file_exists($sassFile)) {
            throw new \Exception(sprintf('Could not find sass file at "%s"', $sassFile));
        }

        if(!is_dir(dirname($cssFile))) {
            mkdir(dirname($cssFile), 0755, true);
        }


        $cmd = sprintf('sass %s %s', $sassFile, $cssFile);

        exec($cmd);

    }
}