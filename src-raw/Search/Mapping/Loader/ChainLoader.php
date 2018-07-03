<?php
namespace Raw\Search\Mapping\Loader;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use Raw\Search\Mapping\ClassMetadata;

use Raw\Search\Mapping;

class ChainLoader implements LoaderInterface
{


    /**
     * @var LoaderInterface[]
     */
    private $loaders = [];

    /**
     * ChainLoader constructor.
     * @param LoaderInterface[] $loaders
     */
    public function __construct(array $loaders)
    {
        $this->loaders = $loaders;
    }

    /**
     * @inheritdoc
     */
    public function loadClassMetadata(ClassMetadata $classMetadata)
    {
        $success = false;
        foreach($this->loaders as $loader) {
            $success = $success || $loader->loadClassMetadata($classMetadata);
        }
        return $success;
    }
}