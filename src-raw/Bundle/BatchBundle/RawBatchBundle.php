<?php
namespace Raw\Bundle\BatchBundle;

use Raw\Bundle\BatchBundle\DependencyInjection\Compiler\RegisterJobsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RawBatchBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterJobsPass());
    }
}