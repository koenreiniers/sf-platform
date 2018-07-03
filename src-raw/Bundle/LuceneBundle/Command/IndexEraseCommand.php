<?php
namespace Raw\Bundle\LuceneBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexEraseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('raw-lucene:index:erase');
        $this->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $registry = $container->get('raw_lucene.registry');

        $name = $input->getArgument('name');

        $registry->eraseIndex($name);
    }
}