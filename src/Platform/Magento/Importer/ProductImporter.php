<?php
namespace Platform\Magento\Importer;

use AppBundle\Entity\Channel;
use Platform\Magento\ArrayConverter\ProductArrayConverter;
use Raw\Component\Mage\MageRestFacade;

class ProductImporter
{
    public function getProductConverter()
    {
        return new ProductArrayConverter([
            'external_id' => 'entity_id',
            'sku' => 'sku',
            'name' => 'name',
            'price' => 'price',
            'external_created_at' => function(array $data) {
                return new \DateTime($data['created_at']);
            },
            'external_updated_at' => function(array $data) {
                return new \DateTime($data['updated_at']);
            },
            'weight' => 'weight',
            'stock_qty' => function(array $data) {
                return $data['stock_data']['qty'];
            },
        ]);
    }

    private function read(Channel $channel, \DateTime $after = null, MageRestFacade $mage)
    {
        $options = $mage->opt();
        if($after !== null) {
            $options->addFilter('updated_at', 'gt', $after->format('Y-m-d H:i:s'));
        }

        $mageProducts = $mage->getProducts($options);
        return $mageProducts;
    }

    private function convert(array $externalProducts)
    {
        $converter = $this->getProductConverter();

        $convertedProducts = $converter->convertAll($externalProducts);

        return $convertedProducts;
    }

    public function import(Channel $channel, \DateTime $after = null, MageRestFacade $mage)
    {
        $mageProducts = $this->read($channel, $after, $mage);

        return $this->convert($mageProducts);

    }
}