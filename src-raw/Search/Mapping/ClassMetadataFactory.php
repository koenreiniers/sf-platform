<?php
namespace Raw\Search\Mapping;

use Raw\Search\Mapping\Loader\LoaderInterface;

class ClassMetadataFactory
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var ClassMetadata[]
     */
    private $loadedMetadata = [];

    /**
     * ClassMetadataFactory constructor.
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param string $className
     * @return ClassMetadata|ClassMetadataBuilder
     * @throws \Exception
     */
    public function getClassMetadata($className)
    {
        if(isset($this->loadedMetadata[$className])) {
            return $this->loadedMetadata[$className];
        }
        $classMetadata = new ClassMetadataBuilder($className);

        if($this->loader->loadClassMetadata($classMetadata) === false) {
            throw new \Exception(sprintf('Failed to load class metadata for "%s"', $className));
        }
        return $this->loadedMetadata[$className] = $classMetadata->compile();
    }

    /**
     * @param string $className
     * @return bool
     */
    public function hasClassMetadata($className)
    {
        try {
            $this->getClassMetadata($className);
        } catch(\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @return ClassMetadata[]
     * @throws \Exception
     */
    public function getAllMetadata()
    {
        $classNames = [News::class, WastedGif::class];
        foreach($classNames as $className) {
            $this->getClassMetadata($className);
        }
        return $this->loadedMetadata;
    }
}