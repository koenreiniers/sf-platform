<?php
namespace Platform\Magento;

use AppBundle\Entity\Channel;
use AppBundle\Entity\Store;
use CatalogBundle\Entity\Product;
use CrmBundle\Entity\Customer;
use Platform\AbstractPlatformAdapter;
use Platform\Magento\Exporter\ShipmentExporter;
use Platform\Magento\Importer\CustomerImporter;
use Platform\Magento\Importer\ProductImporter;
use Platform\Magento\Importer\StoreImporter;
use SalesBundle\Entity\Order;
use Doctrine\ORM\EntityManager;
use Platform\Magento\Importer\OrderImporter;
use AppBundle\Mage\MageRestFactory;
use Platform\PlatformAdapterInterface;
use SalesBundle\Entity\Shipment;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\VarDumper\VarDumper;

class MagentoAdapter extends AbstractPlatformAdapter
{
    /**
     * @var OrderImporter
     */
    private $orderImporter;

    /**
     * @var ShipmentExporter
     */
    private $shipmentExporter;

    /**
     * @var MageRestFactory
     */
    private $mageRestFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var StoreImporter
     */
    private $storeImporter;

    /**
     * @var CustomerImporter
     */
    private $customerImporter;

    /**
     * @var ProductImporter
     */
    private $productImporter;

    public function allowPartialShipments()
    {
        return true;
    }

    /**
     * MagentoAdapter constructor.
     * @param MageRestFactory $mageRestFactory
     * @param OrderImporter $orderImporter
     */
    public function __construct(EntityManager $entityManager, MageRestFactory $mageRestFactory, OrderImporter $orderImporter)
    {
        $this->entityManager = $entityManager;
        $this->mageRestFactory = $mageRestFactory;
        $this->orderImporter = $orderImporter;
        $this->shipmentExporter = new ShipmentExporter();
        $this->storeImporter = new StoreImporter();
        $this->customerImporter = new CustomerImporter();
        $this->productImporter = new ProductImporter();
    }

    /**
     * @param Channel $channel
     * @return Order[]
     */
    public function importOrders(Channel $channel, \DateTime $after = null)
    {
        $mageRest = $this->mageRestFactory->createForChannel($channel);
        return $this->orderImporter->import($channel, $after, $mageRest);
    }

    public function importProducts(Channel $channel, \DateTime $after = null)
    {
        $mage = $this->mageRestFactory->createForChannel($channel);
        return $this->productImporter->import($channel, $after, $mage);
    }

    public function importStores(Channel $channel)
    {
        $mageRest = $this->mageRestFactory->createForChannel($channel);
        return $this->storeImporter->import($channel, $mageRest);
    }

    public function importCustomers(Channel $channel, \DateTime $after = null)
    {
        $mage = $this->mageRestFactory->createForChannel($channel);
        return $this->customerImporter->import($channel, $mage, $after);
    }

    public function exportShipments(Channel $channel)
    {
        $shipments = $this->entityManager->getRepository(Shipment::class)->findShipmentsToExport($channel);
        $mageRest = $this->mageRestFactory->createForChannel($channel);

        return $this->shipmentExporter->export($channel, $shipments, $mageRest);
    }

    public function isAuthorized(Channel $channel)
    {
        $factory = $this->mageRestFactory;
        $mageRest = $factory->createForChannel($channel);
        return $mageRest->getClient()->isAuthorized();
    }

    public function startAuthorization(Channel $channel)
    {
        $this->validateParameters($channel->getParameters());

        $factory = $this->mageRestFactory;
        $mageRest = $factory->createForChannel($channel);

        $mageRest->getClient()->acquireRequestToken();
        $this->entityManager->flush();
        $mageRest->getClient()->redirectForAuthorization();
    }

    public function validateParameters(array $parameters)
    {
        $required = ['url', 'consumer_key', 'consumer_secret'];
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
        $builder->add('url', UrlType::class);
        $builder->add('consumer_key', TextType::class);
        $builder->add('consumer_secret', TextType::class);
    }

    public function configureDefaultParameters(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'url' => null,
            'consumer_key' => null,
            'consumer_secret' => null,
            'access_token' => null,
            'request_token' => null,
        ]);
    }
}