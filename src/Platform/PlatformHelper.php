<?php
namespace Platform;

use AppBundle\Entity\Channel;
use CatalogBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Platform\Helper\CustomerImportHelper;
use Platform\Helper\OrderImportHelper;
use Platform\Helper\ProductImportHelper;
use Platform\Helper\StoreImportHelper;
use SalesBundle\Entity\Order;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\VarDumper\VarDumper;

class PlatformHelper
{
    /**
     * @var PlatformRegistry
     */
    private $registry;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var OrderImportHelper
     */
    private $orderImportHelper;

    /**
     * @var ProductImportHelper
     */
    private $productImportHelper;

    /**
     * @var StoreImportHelper
     */
    private $storeImportHelper;

    /**
     * @var CustomerImportHelper
     */
    private $customerImportHelper;

    /**
     * PlatformHelper constructor.
     * @param PlatformRegistry $registry
     * @param EntityManager $entityManager
     */
    public function __construct(PlatformRegistry $registry, EntityManager $entityManager)
    {
        $this->registry = $registry;
        $this->entityManager = $entityManager;
        $this->orderImportHelper = new OrderImportHelper($entityManager);
        $this->productImportHelper = new ProductImportHelper($entityManager);
        $this->storeImportHelper = new StoreImportHelper($entityManager);
        $this->customerImportHelper = new CustomerImportHelper($entityManager);
    }

    private function persistAll(array $entities)
    {
        foreach($entities as $entity) {
            $this->entityManager->persist($entity);
        }
        return $entities;
    }

    private function persistAllAndFlush(array $entities)
    {
        $this->persistAll($entities);
        $this->entityManager->flush();

        return $entities;
    }

    public function getAdapter(Channel $channel)
    {
        return $this->registry->getPlatformAdapter($channel->getPlatformName());
    }

    public function importOrders(Channel $channel)
    {
        $adapter = $this->getAdapter($channel);

        $after = null;

        $datas = $adapter->importOrders($channel, $after);


        $orders = $this->orderImportHelper->insertOrderData($channel, $datas);
        return $orders;
    }

    public function importProducts(Channel $channel)
    {
        $adapter = $this->getAdapter($channel);

        $productRepository = $this->entityManager->getRepository(Product::class);
        $lastUpdatedProduct = $productRepository->findLastUpdatedProduct($channel);
        $after = null;
        if($lastUpdatedProduct !== null) {
            $after = $lastUpdatedProduct->getExternalUpdatedAt();
        }
        $productDatas = $adapter->importProducts($channel, $after);

        $products = $this->productImportHelper->insertProductData($channel, $productDatas);

        return $products;
    }

    public function importStores(Channel $channel)
    {
        $adapter = $this->getAdapter($channel);

        $items = $adapter->importStores($channel);

        $stores = $this->storeImportHelper->insertStoreData($channel, $items);

        return $stores;
    }

    public function importCustomers(Channel $channel)
    {
        $after = null; // TODO

        $adapter = $this->getAdapter($channel);

        $items = $adapter->importCustomers($channel, $after);

        return $this->customerImportHelper->insertCustomerData($channel, $items);
    }

    /**
     * @param Channel $channel
     */
    public function fillDefaultParameters(Channel $channel)
    {
        $adapter = $this->getAdapter($channel);
        $resolver = new OptionsResolver();
        $adapter->configureDefaultParameters($resolver);
        $parameters = $resolver->resolve($channel->getParameters());
        $channel->setParameters($parameters);
    }

    /**
     * @param Channel $channel
     * @param string $actionName
     * @return \SalesBundle\Entity\Order[]
     * @throws \Exception
     */
    public function executeAction(Channel $channel, $actionName)
    {
        $adapter = $this->getAdapter($channel);
        switch($actionName) {
            case 'import_products':
                $products = $this->importProducts($channel);
                return $this->persistAllAndFlush($products);
                break;
            case 'import_orders':
                $orders = $this->importOrders($channel);

                return $this->persistAllAndFlush($orders);
                break;
            case 'import_stores':
                $stores = $this->importStores($channel);
                return $this->persistAllAndFlush($stores);
                break;
            case 'import_customers':
                $customers = $this->importCustomers($channel);
                return $this->persistAllAndFlush($customers);
                break;
            case 'complete_import':
                $this->executeAction($channel, 'import_stores');
                $this->executeAction($channel, 'import_products');
                $this->executeAction($channel, 'import_customers');
                $this->executeAction($channel, 'import_orders');
                return;
                break;
            case 'export_shipments':
                $shipments = $adapter->exportShipments($channel);
                $this->entityManager->flush();
                return $shipments;
                break;
            case 'complete_export':
                $this->executeAction($channel, 'export_shipments');
                return;
                break;
            case 'complete_sync':
                $this->executeAction($channel, 'complete_export');
                $this->executeAction($channel, 'complete_import');
                return;
                break;
        }
        throw new \Exception(sprintf('Unknown action "%s"', $actionName));
    }
}