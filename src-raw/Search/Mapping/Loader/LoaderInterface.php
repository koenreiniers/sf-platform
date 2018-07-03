<?php
namespace Raw\Search\Mapping\Loader;

use Raw\Search\Mapping\ClassMetadata;

interface LoaderInterface
{
    /**
     * @param ClassMetadata $classMetadata
     *
     * @return bool - true on success, false on failure
     */
    public function loadClassMetadata(ClassMetadata $classMetadata);
}