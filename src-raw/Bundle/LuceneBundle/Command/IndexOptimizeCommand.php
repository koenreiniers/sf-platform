<?php
namespace Raw\Bundle\LuceneBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexOptimizeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('raw-lucene:index:optimize');
        $this->addArgument('id', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $registry = $container->get('raw_lucene.registry');

        $name = $input->getArgument('name');

        $index = $registry->getIndex($name);

        $index->optimize();
    }
}