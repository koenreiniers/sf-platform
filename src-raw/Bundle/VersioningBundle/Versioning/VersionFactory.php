<?php
namespace Raw\Bundle\VersioningBundle\Versioning;

use Raw\Bundle\VersioningBundle\Behaviour\VersionableInterface;
use Raw\Bundle\VersioningBundle\Entity\Version;
use Raw\Bundle\VersioningBundle\Repository\VersionRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Serializer;

class VersionFactory
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var VersionRepository
     */
    private $versionRepository;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * VersionFactory constructor.
     * @param Serializer $serializer
     * @param VersionRepository $versionRepository
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(Serializer $serializer, VersionRepository $versionRepository, TokenStorageInterface $tokenStorage)
    {
        $this->serializer = $serializer;
        $this->versionRepository = $versionRepository;
        $this->tokenStorage = $tokenStorage;
    }


    public function create(VersionableInterface $resource)
    {
        $version = new Version();
        $version->setResourceId($resource->getId());
        $version->setResourceName(get_class($resource));

        $snapshot = $this->serializer->normalize($resource);
        $version->setSnapshot($snapshot);

        $previousSnap = [];
        $previousVersion = $this->versionRepository->findPreviousVersion($resource);
        if($previousVersion !== null) {
            $version->setNumber($previousVersion->getNumber() + 1);
            $previousSnap = $previousVersion->getSnapshot();
        }
        $changeSet = $this->calculateChangeSet($previousSnap, $version->getSnapshot());
        $version->setChangeSet($changeSet);

        $version->setAuthor($this->tokenStorage->getToken()->getUsername());

        return $version;
    }

    private function calculateChangeSet(array $previousSnap, array $currentSnap)
    {
        $changeSet = [];

        foreach($previousSnap as $key => $previousValue) {
            $currentValue = isset($currentSnap[$key]) ? $currentSnap[$key] : null;
            if($previousValue === $currentValue) {
                continue;
            }
            $changeSet[$key] = [$previousValue, $currentValue];
        }
        foreach($currentSnap as $key => $currentValue) {
            $previousValue = isset($previousSnap[$key]) ? $previousSnap[$key] : null;
            if($previousValue === $currentValue) {
                continue;
            }
            $changeSet[$key] = [$previousValue, $currentValue];
        }
        return $changeSet;
    }
}