<?php
namespace Platform\Magento\Importer;

use AppBundle\Entity\Channel;
use AppBundle\Mage\MageRestFactory;
use CrmBundle\Entity\Address;
use CrmBundle\Entity\Customer;
use Platform\Helper\OrderImportHelper;
use Platform\Magento\ArrayConverter\DefaultArrayConverter;
use SalesBundle\Entity\Order;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Raw\Component\Mage\GetOptions;
use Raw\Component\Mage\MageRestFacade;
use SalesBundle\Entity\OrderItem;
use Symfony\Component\VarDumper\VarDumper;

class OrderImporter
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var MageRestFactory
     */
    private $mageRestFactory;

    /**
     * OrderImporter constructor.
     * @param EntityManager $entityManager
     * @param MageRestFactory $mageRestFactory
     */
    public function __construct(EntityManager $entityManager, MageRestFactory $mageRestFactory)
    {
        $this->entityManager = $entityManager;
        $this->mageRestFactory = $mageRestFactory;
    }

    public function importOrder(Order $order)
    {
        $mageRest = $this->mageRestFactory->createForChannel($order->getStore()->getChannel());

        $mageOrder = $mageRest->getOrder($order->getExternalId());

        $mageOrder = $this->getOrderConverter()->convert($mageOrder);

        $mageOrders = [$mageOrder];

        $helper = new OrderImportHelper($this->entityManager);

        $helper->insertOrderData($order->getStore()->getChannel(), $mageOrders);

    }

    public function convertOrderState(array $mageOrder)
    {
        $map = [
            'new' => Order::STATE_NEW, // not yet paid
            'processing' => Order::STATE_PROCESSING, // paid
            'complete' => Order::STATE_COMPLETED, // completed
            'holded' => 'on_hold',
            'closed' => 'closed',
            'canceled' => 'cancelled',
        ];

        $state = $map[$mageOrder['state']];
        return $state;
    }

    public function getAddressConverter()
    {
        $map = [
            'first_name' => 'firstname',
            'last_name' => 'lastname',
        ];
        $directCopy = ['street', 'city', 'company'];

        foreach($directCopy as $propName) {
            $map[$propName] = $propName;
        }
        return new DefaultArrayConverter($map);
    }

    public function getOrderItemConverter()
    {
        $map = [
            'external_id' => 'item_id',
        ];
        $directCopy = ['sku','name','qty_ordered','qty_shipped','price','row_total','tax_amount','tax_percent'];

        foreach($directCopy as $propName) {
            $map[$propName] = $propName;
        }
        return new DefaultArrayConverter($map);
    }

    public function getOrderConverter()
    {
        $orderItemConverter = $this->getOrderItemConverter();
        $addressConverter = $this->getAddressConverter();
        $directCopy = ['grand_total', 'subtotal', 'tax_amount', 'shipping_amount', 'discount_amount', 'customer_id'];
        $map = [
            'order_number' => 'increment_id',
            'external_id' => 'entity_id',
            'state' => function(array $data) {
                return $this->convertOrderState($data);
            },
            'order_date' => function(array $data) {
                return new \DateTime($data['created_at']);
            },
            'external_created_at' => function(array $data) {
                return new \DateTime($data['created_at']);
            },
            'order_items' => function(array $data) use($orderItemConverter) {
                $converted = [];
                foreach($data['order_items'] as $orderItemData) {
                    $converted[] = $orderItemConverter->convert($orderItemData);
                }
                return $converted;
            },
            'store_external_id' => 'website_id',
            'billing_address' => function(array $data) use($addressConverter) {
                foreach($data['addresses'] as $addressData) {
                    if($addressData['address_type'] === 'billing') {
                        return $addressConverter->convert($addressData);
                    }
                }
                return null;
            },
            'shipping_address' => function(array $data) use($addressConverter) {
                foreach($data['addresses'] as $addressData) {
                    if($addressData['address_type'] === 'shipping') {
                        return $addressConverter->convert($addressData);
                    }
                }
                return null;
            },
        ];
        foreach($directCopy as $propName) {
            $map[$propName] = $propName;
        }
        return new DefaultArrayConverter($map);
    }

    public function import(Channel $channel, \DateTime $after = null, MageRestFacade $mage)
    {
        $options = $mage->opt();

        if($after !== null) {
            $options->addFilter('updated_at', 'gt', $after->format('Y-m-d H:i:s'));
        }

        $mageOrders = $mage->getOrders($options);

        $mageOrders = $this->getOrderConverter()->convertAll($mageOrders);

        return $mageOrders;


    }
}