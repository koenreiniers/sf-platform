<?php
namespace Raw\Bundle\SassBundle;

use Raw\Component\Statistics\DependencyInjection\RegisterStatisticLoadersPass;
use Raw\Component\Widget\DependencyInjection\RegisterWidgetsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RawSassBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {

    }
}