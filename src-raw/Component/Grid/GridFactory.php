<?php
namespace Raw\Component\Grid;

use Raw\Component\Grid\Mapping\GridMetadataFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;

class GridFactory
{
    /**
     * @var GridRegistry
     */
    private $registry;

    /**
     * @var GridMetadataFactory
     */
    private $metadataFactory;

    /**
     * GridFactory constructor.
     * @param GridRegistry $registry
     * @param GridMetadataFactory $metadataFactory
     */
    public function __construct(GridRegistry $registry, GridMetadataFactory $metadataFactory)
    {
        $this->registry = $registry;
        $this->metadataFactory = $metadataFactory;
    }


    public function getGrid($name, array $parameters = [])
    {
        $metadata = $this->metadataFactory->getMetadataFor($name);

        $builder = new GridBuilder($metadata, new EventDispatcher(), $this->registry->getExtensions());

        foreach($this->registry->getExtensions() as $extension) {
            $extension->build($builder);
        }

        $grid = $builder->getGrid();
        foreach($parameters as $name => $value) {
            $grid->setParameter($name, $value);
        }
        return $grid;
    }
}