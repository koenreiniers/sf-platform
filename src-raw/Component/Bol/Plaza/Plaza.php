<?php
namespace Raw\Component\Bol\Plaza;

use Raw\Component\Bol\Plaza\Http\PlazaClient;
use Raw\Component\Bol\Plaza\Model\AddressDetails;
use Raw\Component\Bol\Plaza\Model\Offer;
use Raw\Component\Bol\Plaza\Model\Order;
use Raw\Component\Bol\Plaza\Model\OrderItem;
use Raw\Component\Bol\Plaza\Model\ProcessStatus;
use Raw\Component\Bol\Plaza\Model\ShipmentRequest;
use Raw\Component\Bol\Plaza\Normalizer\DefaultObjectNormalizer;
use Raw\Component\Bol\Plaza\Normalizer\ProcessStatusNormalizer;
use Raw\Component\Bol\Plaza\Normalizer\ShipmentRequestNormalizer;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\VarDumper\VarDumper;

class Plaza
{
    /**
     * @var PlazaClient
     */
    protected $client;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * Plaza constructor.
     * @param PlazaClient $client
     */
    public function __construct(PlazaClient $client)
    {
        $this->client = $client;

        $normalizers = [
            new ShipmentRequestNormalizer(),
            new ProcessStatusNormalizer(),
            new DefaultObjectNormalizer(),
        ];
        $encoders = [
            new XmlEncoder(),
        ];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @param string $filename
     *
     * @return ResponseInterface
     */
    public function checkExport($filename)
    {
        $uri = sprintf('/offers/v1/export/%s.csv', $filename);

        $response = $this->client->get($uri);

        return $response;
    }

    /**
     * Returns the export filename
     *
     * @param null|bool $published
     *
     * @return string
     */
    public function requestExport($published = null)
    {
        $uri = '/offers/v1/export';

        if($published !== null) {
            $uri .= '?filter='. $published ? 'PUBLISHED' : 'NOT-PUBLISHED';
        }

        $response = $this->client->get($uri);

        $body = (string)$response->getBody();

        $data = $this->serializer->decode($body, 'xml');

        $url = $data['Url'];

        $parts = explode('/', $url);

        $filename = array_pop($parts);

        return $filename;
    }

    /**
     * @param Offer $offer
     *
     * @return ResponseInterface
     */
    public function updateOfferStock(Offer $offer)
    {
        $uri = '/offers/v1/'.$offer->getId();

        $body = sprintf('<?xml version="1.0" encoding="UTF-8"?>
<StockUpdate xmlns="http://plazaapi.bol.com/offers/xsd/api-1.0.xsd">
<QuantityInStock>%s</QuantityInStock>
</StockUpdate>', $offer->getQuantityInStock());

        $response = $this->client->put($uri, $body);

        return $response;
    }

    /**
     * @param Offer $offer
     *
     * @return ResponseInterface
     */
    public function updateOffer(Offer $offer)
    {
        $uri = '/offers/v1/'.$offer->getId();

        $body = sprintf('<?xml version="1.0" encoding="UTF-8"?>
<OfferUpdate xmlns="http://plazaapi.bol.com/offers/xsd/api-1.0.xsd">
<Price>%s</Price>
<DeliveryCode>%s</DeliveryCode>
<Publish>%s</Publish>
<ReferenceCode>%s</ReferenceCode>
<Description>%s</Description>
</OfferUpdate>',
            $offer->getPrice(),
            $offer->getDeliveryCode(),
            $offer->isPublish() ? 'true' : 'false',
            $offer->getReferenceCode(),
            $offer->getDescription()
        );

        $response = $this->client->put($uri, $body);

        return $response;
    }

    /**
     * @param string $id
     *
     * @return ResponseInterface
     */
    public function deleteOffer($id)
    {
        $uri = '/offers/v1/'.$id;

        $response = $this->client->delete($uri);

        return $response;
    }

    /**
     * @param Offer $offer
     *
     * @return ResponseInterface
     */
    public function createOffer(Offer $offer)
    {
        $uri = '/offers/v1/'.$offer->getId();

        $body = sprintf('<?xml version="1.0" encoding="UTF-8"?>
<OfferCreate xmlns="http://plazaapi.bol.com/offers/xsd/api-1.0.xsd">
<EAN>%s</EAN>
<Condition>%s</Condition>
<Price>%s</Price>
<DeliveryCode>%s</DeliveryCode>
<QuantityInStock>%s</QuantityInStock>
<Publish>%s</Publish>
<ReferenceCode>%s</ReferenceCode>
<Description>%s</Description>
</OfferCreate>',
            $offer->getEan(),
            $offer->getCondition(),
            $offer->getPrice(),
            $offer->getDeliveryCode(),
            $offer->getQuantityInStock(),
            $offer->isPublish() ? 'true' : 'false',
            $offer->getReferenceCode(),
            $offer->getDescription()
        );

        $response = $this->client->post($uri, $body);

        return $response;
    }

    /**
     * @param ShipmentRequest $shipmentRequest
     *
     * @return ProcessStatus
     */
    public function createShipment(ShipmentRequest $shipmentRequest)
    {
        $body = $this->serialize($shipmentRequest, 'ShipmentRequest');
        
        $response = $this->client->post('/services/rest/shipments/v2', $body);

        return $this->deserialize($response);
    }

    /**
     * @param mixed $data
     * @param string $rootNodeName
     *
     * @return string
     */
    private function serialize($data, $rootNodeName)
    {
        $normalized = $this->serializer->normalize($data, 'xml');
        $normalized['@xmlns'] = 'https://plazaapi.bol.com/services/xsd/v2/plazaapi.xsd';
        return $this->serializer->encode($normalized, 'xml', [
            'xml_root_node_name' => $rootNodeName,
        ]);
    }

    /**
     * @param ResponseInterface $response
     * @param string $type
     *
     * @return object
     */
    private function deserialize(ResponseInterface $response, $type = ProcessStatus::class)
    {
        $data = $this->serializer->decode((string)$response->getBody(), 'xml');
        return $this->serializer->denormalize($data, $type);
    }

    private function decodeResponse(ResponseInterface $response)
    {
        $body = (string)$response->getBody();

        $decoded = $this->serializer->decode($body, 'xml');

        return $decoded;
    }

    private function fixArray(array $items, $singleName)
    {
        $fixed = $items[$singleName];
        if(!array_key_exists(0, $fixed)) {
            $fixed = [$fixed];
        }
        return $fixed;
    }

    /**
     * @return array
     */
    public function getOpenOrders()
    {
        $response = $this->client->get('/services/rest/orders/v2');


        $decoded = $this->decodeResponse($response);

        if(!isset($decoded['Order'])) {
            return [];
        }

        $orders = $this->fixArray($decoded, 'Order');

        foreach($orders as &$order) {

            $orderItems = $this->fixArray($order['OrderItems'], 'OrderItem');

            $order['OrderItems'] = $orderItems;
        }

        return $orders;
    }
}