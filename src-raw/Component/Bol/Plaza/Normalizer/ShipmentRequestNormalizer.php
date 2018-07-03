<?php
namespace Raw\Component\Bol\Plaza\Normalizer;

use Raw\Component\Bol\Plaza\Model\ShipmentRequest;
use Raw\Component\Bol\Plaza\Model\Transport;

class ShipmentRequestNormalizer extends DefaultObjectNormalizer
{
    protected function init($object, $format = null, array $context = array())
    {
        $this->setCallback('transport', function(Transport $transport) {
            return [
                'TransporterCode' => $transport->getTransporterCode(),
                'TrackAndTrace' => $transport->getTrackAndTrace(),
            ];
        });

        $formatDate = function(\DateTime $dateTime) {
            return $dateTime->format(\DATE_ATOM);
        };

        $this->setCallback('dateTime', $formatDate);
        $this->setCallback('expectedDeliveryDate', $formatDate);

        $this->setPropertyMap([
            'OrderItemId' => 'orderItemId',
            'ShipmentReference' => 'shipmentReference',
            'DateTime' => 'dateTime',
            'ExpectedDeliveryDate' => 'expectedDeliveryDate',
            'Transport' => 'transport',
        ]);
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof ShipmentRequest;
    }
}