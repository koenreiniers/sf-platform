<?php
namespace Raw\Bundle\VersioningBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Serializer\DependencyInjection\SerializerPass;

class RawVersioningBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SerializerPass('raw_versioning.serializer', 'raw_versioning.normalizer', 'raw_versioning.encoder'));
    }
}