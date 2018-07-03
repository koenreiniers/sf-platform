<?php
namespace Platform\Helper;

use AppBundle\Entity\Channel;
use CrmBundle\Entity\Address;
use CrmBundle\Entity\Customer;
use SalesBundle\Entity\Order;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use SalesBundle\Entity\OrderItem;

class OrderImportHelper
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * OrderImportHelper constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function createOrder(array $data)
    {
        $order = new Order();
        $order->setCreatedAt(new \DateTime());

        $billingAddress = $this->createAddress($data['billing_address']);
        $this->updateAddress($billingAddress, $data['billing_address']);
        $order->setBillingAddress($billingAddress);
        $shippingAddress = $this->createAddress($data['shipping_address']);
        $this->updateAddress($shippingAddress, $data['shipping_address']);
        $order->setShippingAddress($shippingAddress);

        return $order;
    }

    private function createAddress(array $data)
    {
        $address = new Address();
        return $address;
    }

    private function updateAddress(Address $address, array $data)
    {
        $address->setFirstName($data['first_name']);
        $address->setLastName($data['last_name']);
        $address->setCompany($data['company']);
        $address->setStreet($data['street']);
        $address->setCity($data['city']);
    }

    private function updateOrder(Order $order, array $data)
    {
        if(isset($data['customer_id'])) {
            $customer = $this->entityManager->getRepository(Customer::class)->findOneBy([
                'channel' => $order->getStore()->getChannel(),
                'externalId' => $data['customer_id'],
            ]);
            if($customer !== null) {
                $order->setCustomer($customer);
                $customer->addOrder($order);
            }
        }

        $itemsByExternalId = [];
        foreach($order->getOrderItems() as $item) {
            $itemsByExternalId[$item->getExternalId()] = $item;
        }
        $order->setOrderNumber($data['order_number']);

        // Totals
        $order->setGrandTotal($data['grand_total']);
        $order->setSubtotal($data['subtotal']);
        $order->setTaxAmount($data['tax_amount']);
        $order->setShippingAmount($data['shipping_amount']);
        $order->setDiscountAmount($data['discount_amount']);


        $order->setState($data['state']);
        $order->setOrderDate($data['order_date']);
        $order->setExternalCreatedAt($data['external_created_at']);

        foreach($data['order_items'] as $orderItemData) {
            $itemExternalId = $orderItemData['external_id'];
            if(!isset($itemsByExternalId[$itemExternalId])) {
                $orderItem = $this->createOrderItem($orderItemData);
                $orderItem->setOrder($order);
                $order->addOrderItem($orderItem);
                $itemsByExternalId[$itemExternalId] = $orderItem;
            }
            /** @var OrderItem $orderItem */
            $orderItem = $itemsByExternalId[$itemExternalId];
            $this->updateOrderItem($orderItem, $orderItemData);
        }
    }


    /**
     *
     * @param array $data
     *
     * @return OrderItem
     */
    private function createOrderItem(array $data)
    {
        $orderItem = new OrderItem();
        $orderItem->setExternalId($data['external_id']);

        return $orderItem;
    }

    private function updateOrderItem(OrderItem $orderItem, array $data)
    {
        $orderItem->setSku($data['sku']);
        $orderItem->setName($data['name']);
        $orderItem->setQtyOrdered($data['qty_ordered']);
        $orderItem->setQtyShipped($data['qty_shipped']);
        $orderItem->setPrice($data['price']);
        $orderItem->setRowTotal($data['row_total']);
        $orderItem->setTaxAmount($data['tax_amount']);
        $orderItem->setTaxPercent($data['tax_percent']);
    }

    public function insertOrderData(Channel $channel, array $orderDatas)
    {
        $orderRepository = $this->entityManager->getRepository(Order::class);

        $stores = $channel->getStores();

        $storesByExternalId = [];
        foreach($stores as $store) {
            $storesByExternalId[$store->getExternalId()] = $store;
        }

        $orders = [];
        foreach($orderDatas as $orderData) {

            $storeExternalId = $orderData['store_external_id'];

            if(!isset($storesByExternalId[$storeExternalId])) {
                // Log missing store
                continue;
            }

            $store = $storesByExternalId[$storeExternalId];

            $orderExternalId = $orderData['external_id'];

            $order = $orderRepository->findOneBy([
                'store' => $store,
                'externalId' => $orderExternalId,
            ]);

            if($order === null) {
                $order = $this->createOrder($orderData);
                $order->setExternalId($orderExternalId);
                $order->setStore($store);
            }

            $this->updateOrder($order, $orderData);

            $orders[] = $order;
        }


        return $orders;
    }
}