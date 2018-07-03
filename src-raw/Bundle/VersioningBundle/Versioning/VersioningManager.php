<?php
namespace Raw\Bundle\VersioningBundle\Versioning;

use Doctrine\ORM\EntityManager;
use Raw\Bundle\VersioningBundle\Behaviour\VersionableInterface;
use Raw\Bundle\VersioningBundle\Entity\Version;
use Raw\Bundle\VersioningBundle\Repository\VersionRepository;
use Symfony\Component\Serializer\Serializer;

class VersioningManager
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var VersionFactory
     */
    private $versionFactory;

    /**
     * VersioningManager constructor.
     * @param EntityManager $entityManager
     * @param Serializer $serializer
     */
    public function __construct(EntityManager $entityManager, Serializer $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        global $kernel;

        $factory = $kernel->getContainer()->get('raw_versioning.factory.version');
        $this->versionFactory = $factory;
    }

    public function restore(Version $version)
    {
        $resource = $this->entityManager->getRepository($version->getResourceName())->find($version->getResourceId());

        $resource = $this->serializer->denormalize($version->getSnapshot(), get_class($resource), null, [
            'object' => $resource,
        ]);

        $version = $this->versionFactory->create($resource);
        $version->setContext(Version::CONTEXT_RESTORE);
        $this->entityManager->persist($version);

        $this->entityManager->flush();
    }
}