<?php
namespace Platform\Helper;

use AppBundle\Entity\Channel;
use CatalogBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ProductImportHelper
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ProductImportHelper constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    protected function denormalize($entity, array $data, array $props)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach($props as $prop) {
            if(!array_key_exists($prop, $data)) {
                continue;
            }
            $accessor->setValue($entity, $prop, $data[$prop]);
        }
    }

    private function denormalizeProduct(Product $product, array $data)
    {
        $props = ['sku','name','price', 'external_created_at', 'external_updated_at', 'stock_qty', 'weight'];
        $this->denormalize($product, $data, $props);
    }

    public function updateProduct(Product $product, array $productData)
    {
        $this->denormalizeProduct($product, $productData);

        return $product;
    }

    public function insertProductData(Channel $channel, array $productDatas)
    {

        $products = [];

        foreach($productDatas as $productData) {
            $productRepository = $this->entityManager->getRepository(Product::class);
            $product = $productRepository->findOneBy([
                'externalId' => $productData['external_id'],
                'channel' => $channel,
            ]);

            if($product === null) {
                $product = new Product();
                $product->setChannel($channel);
                $channel->addProduct($product);
                $product->setExternalId($productData['external_id']);
            }

            $this->updateProduct($product, $productData);
            $products[] = $product;
        }
        return $products;
    }
}