<?php
namespace Raw\Bundle\SearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexOptimizeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('raw-search:index:optimize');
        $this->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $registry = $container->get('raw_search.registry');

        $name = $input->getArgument('name');

        $searchIndex = $registry->getIndex($name);

        $searchIndex->optimize();
    }
}