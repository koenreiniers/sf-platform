<?php
namespace Raw\Bundle\DashboardBundle;

use Raw\Component\Statistics\DependencyInjection\RegisterStatisticLoadersPass;
use Raw\Component\Widget\DependencyInjection\RegisterWidgetsPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RawDashboardBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterWidgetsPass());
        $container->addCompilerPass(new RegisterStatisticLoadersPass());
        $container->addCompilerPass(new RegisterStatisticLoadersPass('dataset.loader', 'raw_dashboard.dataset.loader.chain'));
    }
}