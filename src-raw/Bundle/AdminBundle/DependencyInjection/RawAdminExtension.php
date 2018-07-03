<?php
namespace Raw\Bundle\AdminBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\VarDumper\VarDumper;

class RawAdminExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {

        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('raw_admin.templates', $config['templates']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.yml');
    }
}