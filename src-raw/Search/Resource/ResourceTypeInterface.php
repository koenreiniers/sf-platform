<?php
namespace Raw\Search\Resource;

use Raw\Search\Mapping\ClassMetadata;

interface ResourceTypeInterface
{
    /**
     * @param ClassMetadata $classMetadata
     * @param mixed $expr
     * @return object[]
     */
    public function findBy(ClassMetadata $classMetadata, $expr);

    /**
     * @param ClassMetadata $classMetadata
     * @return object[]
     */
    public function findAllResources(ClassMetadata $classMetadata);
}