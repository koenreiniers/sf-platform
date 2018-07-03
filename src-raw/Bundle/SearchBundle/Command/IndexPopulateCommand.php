<?php
namespace Raw\Bundle\SearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper\VarDumper;

class IndexPopulateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('raw-search:index:populate');
        $this->addArgument('name', InputArgument::REQUIRED);
        $this->addOption('optimize', 'o', InputOption::VALUE_NONE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $registry = $container->get('raw_search.registry');

        $name = $input->getArgument('name');



        $index = $registry->getIndex($name);

        $index->erase();

        $registry->populate($index);

        if($input->getOption('optimize') === true) {
            $index->optimize();
        }
    }
}