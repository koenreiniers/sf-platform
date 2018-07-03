<?php
namespace AppBundle\Batch\Step;

use Raw\Component\Batch\StepExecution;
use Raw\Component\Batch\StepInterface;
use ShippingBundle\Shipping\Shipping;

class ProcessShipmentTracksStep implements StepInterface
{
    /**
     * @var Shipping
     */
    private $shipping;

    /**
     * ProcessShipmentTracksStep constructor.
     * @param Shipping $shipping
     */
    public function __construct(Shipping $shipping)
    {
        $this->shipping = $shipping;
    }

    public function execute(StepExecution $stepExecution)
    {

        $tracks = $this->shipping->processShipmentTracks();

        $stepExecution->incrementSummaryInfo('processed_items', count($tracks));
    }

    public function getName()
    {
        return 'Process shipment tracks';
    }
}