<?php
namespace ShippingBundle\Carrier;

use SalesBundle\Entity\ShipmentTrack;

class CarrierRegistry
{
    /**
     * @var CarrierAdapterInterface[]
     */
    private $adapters;

    /**
     * CarrierRegistry constructor.
     * @param CarrierAdapterInterface[] $adapters
     */
    public function __construct(array $adapters)
    {
        $this->adapters = $adapters;
    }

    /**
     * @param string $name
     * @return CarrierAdapterInterface
     */
    public function getAdapter($name)
    {
        return $this->adapters[$name];
    }
}