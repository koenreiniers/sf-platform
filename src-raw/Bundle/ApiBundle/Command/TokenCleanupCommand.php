<?php
namespace Raw\Bundle\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TokenCleanupCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('raw-api:token:cleanup');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $repository = $this->getContainer()->get('raw_api.repository.token');

        $tokens = $repository->findExpiredTokens();

        foreach($tokens as $token) {
            $em->remove($token);
        }

        $em->flush();

        $output->writeln(sprintf('Removed %s tokens', count($tokens)));
    }
}