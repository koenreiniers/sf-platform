<?php
namespace Platform\Bol;

use AppBundle\Entity\Channel;
use Platform\AbstractPlatformAdapter;
use Platform\Bol\ArrayConverter\AddressConverter;
use Platform\Magento\ArrayConverter\DefaultArrayConverter;
use Raw\Component\Bol\Plaza\Model\ShipmentRequest;
use Raw\Component\Bol\Plaza\Model\Transport;
use Raw\Component\Bol\Plaza\PlazaFactory;
use SalesBundle\Entity\Order;
use Doctrine\ORM\EntityManager;
use SalesBundle\Entity\Shipment;
use SalesBundle\Entity\ShipmentTrack;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\VarDumper\VarDumper;

class BolPlatformAdapter extends AbstractPlatformAdapter
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * BolAdapter constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getOrderItemConverter()
    {
        $map = [
            'external_id' => 'OrderItemId',
            'sku' => function(array $data){
                if(isset($data['OfferReference'])) {
                    return $data['OfferReference'];
                }
                return $data['Title'];
            },
            'name' => 'Title',
            'qty_ordered' => 'Quantity',
            'qty_shipped' => function() {
                return 0;
            },
            'price' => 'OfferPrice',
            'row_total' => 'OfferPrice',
            'tax_amount' => function() {
                return 0;
            },
            'tax_percent' => function() {
                return 0;
            },
        ];
        return new DefaultArrayConverter($map);
    }

    public function getAddressConverter()
    {
        return new AddressConverter();
    }

    public function getOrderConverter()
    {
        $orderItemConverter = $this->getOrderItemConverter();
        $addressConverter = $this->getAddressConverter();
        $map = [
            'order_number' => 'OrderId',
            'external_id' => 'OrderId',
            'state' => function(array $data) {
                return Order::STATE_PROCESSING;
            },
            'order_date' => function(array $data) {
                return new \DateTime($data['DateTimeCustomer']);
            },
            'external_created_at' => function(array $data) {
                return new \DateTime($data['DateTimeCustomer']);
            },
            'order_items' => function(array $data) use($orderItemConverter) {
                $converted = [];
                foreach($data['OrderItems'] as $orderItemData) {
                    $converted[] = $orderItemConverter->convert($orderItemData);
                }
                return $converted;
            },
            'store_external_id' => function(array $data) {
                return 'n/a';
            },
            'billing_address' => function(array $data) use($addressConverter) {
                $addressData = $data['CustomerDetails']['BillingDetails'];
                return $addressConverter->convert($addressData);
            },
            'shipping_address' => function(array $data) use($addressConverter) {
                $addressData = $data['CustomerDetails']['ShipmentDetails'];
                return $addressConverter->convert($addressData);
            },
            'grand_total' => function(array $data) {
                $total = 0;
                foreach($data['OrderItems'] as $orderItem) {
                    $total += $orderItem['Quantity'] * $orderItem['OfferPrice'];
                }
                return $total;
            },
            'subtotal' => function(array $data) {
                $total = 0;
                foreach($data['OrderItems'] as $orderItem) {
                    $total += $orderItem['Quantity'] * $orderItem['OfferPrice'];
                }
                return $total;
            },
            'tax_amount' => function() {
                return 0;
            },
            'tax_percent' => function() {
                return 0;
            },
            'shipping_amount' => function() {
                return 0;
            },
            'discount_amount' => function() {
                return 0;
            }
        ];
        return new DefaultArrayConverter($map);
    }

    public function createPlazaForChannel(Channel $channel)
    {
        $plazaFactory = new PlazaFactory();
        $plaza = $plazaFactory->create($channel->getParameter('public_key'), $channel->getParameter('private_key'), true);
        return $plaza;
    }

    public function importOrders(Channel $channel, \DateTime $after = null)
    {
        $plaza = $this->createPlazaForChannel($channel);

        $readItems = $plaza->getOpenOrders();


        $convertedItems = $this->getOrderConverter()->convertAll($readItems);

        #VarDumper::dump($convertedItems);die;

        return $convertedItems;
    }

    public function importProducts(Channel $channel, \DateTime $after = null)
    {
        return [];
    }

    public function importStores(Channel $channel)
    {
        $stores = [];
        $stores[] = [
            'name' => 'Default',
            'external_id' => 'n/a',
        ];
        return $stores;
    }

    public function importCustomers(Channel $channel, \DateTime $after = null)
    {
        return [];
    }

    public function exportShipments(Channel $channel)
    {
        $shipments = $this->entityManager->getRepository(Shipment::class)->findShipmentsToExport($channel);

        $plaza = $this->createPlazaForChannel($channel);

        foreach($shipments as $shipment) {


            /** @var ShipmentTrack|false $shipmentTrack */
            $shipmentTrack = $shipment->getShipmentTracks()->first();

            foreach($shipment->getShipmentItems() as $shipmentItem) {

                $bolShipmentRequest = ShipmentRequest::create()
                    ->setShipmentReference($shipment->getId())
                    ->setOrderItemId($shipmentItem->getOrderItem()->getExternalId())
                ;
                if($shipmentTrack) {
                    $bolShipmentRequest->setTransport(
                        Transport::create()
                            ->setTrackAndTrace($shipmentTrack->getTrackingNumber())
                            ->setTransporterCode($shipmentTrack->getCarrier()->getCode())
                    );
                }
                $processStatus = $plaza->createShipment($bolShipmentRequest);
                VarDumper::dump($processStatus);
            }

        }

        return $shipments;
    }

    public function isAuthorized(Channel $channel)
    {
        return !empty($channel->getParameter('public_key')) && !empty($channel->getParameter('private_key'));
    }

    public function startAuthorization(Channel $channel)
    {
        throw new \Exception('Not supported');
    }

    public function validateParameters(array $parameters)
    {
        $required = ['public_key', 'private_key'];
        $errors = [];
        foreach($required as $parameterName) {
            if(!isset($parameters[$parameterName]) || $parameters[$parameterName] === null) {
                $errors[] = sprintf('Parameter "%s" must not be empty.', $parameterName);
            }
        }
        if(count($errors) > 0) {
            throw new \Exception(implode(' ', $errors));
        }
    }

    public function buildParameterForm(FormBuilderInterface $builder)
    {
        $builder->add('public_key', TextareaType::class);
        $builder->add('private_key', TextareaType::class);
    }

    public function configureDefaultParameters(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'public_key' => null,
            'private_key' => null,
        ]);
    }
}