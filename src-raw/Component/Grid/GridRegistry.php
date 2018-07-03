<?php
namespace Raw\Component\Grid;

class GridRegistry
{
    /**
     * @var GridExtension[]
     */
    private $extensions;

    public function __construct(array $extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * @return GridExtension[]
     */
    public function getExtensions()
    {
        return $this->extensions;
    }
}