<?php
namespace Platform\Magento\Exporter;

use AppBundle\Entity\Channel;
use Raw\Component\Mage\MageRestFacade;
use SalesBundle\Entity\Shipment;
use SalesBundle\Entity\ShipmentTrack;
use Symfony\Component\VarDumper\VarDumper;

class ShipmentExporter
{
    /**
     * @param Channel $channel
     * @param Shipment[] $shipments
     *
     * @return Shipment[]
     */
    public function export(Channel $channel, array $shipments, MageRestFacade $mageRest)
    {

        foreach($shipments as $shipment) {
            $this->exportShipment($channel, $shipment, $mageRest);
        }

        return $shipments;
    }

    private function exportShipment(Channel $channel, Shipment $shipment, MageRestFacade $mageRest)
    {


        $success = true;



        if($shipment->getExternalId() === null) {

            $comment = null;
            $email = false;
            $includeComment = false;

            $qtyPerItemId = [];

            foreach($shipment->getShipmentItems() as $shipmentItem) {
                $mageOrderId = $shipmentItem->getOrderItem()->getExternalId();
                $qty = $shipmentItem->getQty();
                $qtyPerItemId[$mageOrderId] = $qty;
            }

            try {
                $shipmentId = $mageRest->createShipment($shipment->getOrder()->getExternalId(), $qtyPerItemId, $comment, $email, $includeComment);
                $shipment->setExternalId($shipmentId);
            } catch(\Exception $e) {
                $success = false;
                // TODO: Add error message to shipment
            }
        }


        foreach($shipment->getShipmentTracks() as $shipmentTrack) {
            try {

                $mageRest->getClient()->post('shipment-tracks', json_encode([
                    'shipment_id' => $shipment->getExternalId(),
                    'carrier' => $shipmentTrack->getCarrier()->getCode(),
                    'title' => $shipmentTrack->getTitle(),
                    'track_number' => $shipmentTrack->getTrackingNumber(),
                ]));
                $shipmentTrack->setState(ShipmentTrack::STATE_COMPLETED);
            } catch(\Exception $e) {
                $success = false;
                // TODO: Add error message
            }

        }
        if($success) {
            $shipment->setState(Shipment::STATE_COMPLETED);
        }



    }
}