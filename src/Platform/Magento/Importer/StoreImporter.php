<?php
namespace Platform\Magento\Importer;

use AppBundle\Entity\Channel;
use AppBundle\Entity\Store;
use Doctrine\ORM\EntityRepository;
use Platform\Magento\ArrayConverter\DefaultArrayConverter;
use Raw\Component\Mage\MageRestFacade;
use Symfony\Component\VarDumper\VarDumper;

class StoreImporter
{

    protected function read(Channel $channel, MageRestFacade $mage)
    {
        $websites = $mage->getWebsites();
        return $websites;
    }

    public function getConverter()
    {
        $map = [
            'external_id' => 'id',
            'name' => 'name',
        ];
        return new DefaultArrayConverter($map);
    }

    protected function convert(array $readItems)
    {
        return $this->getConverter()->convertAll($readItems);
    }

    public function import(Channel $channel, MageRestFacade $mage)
    {
        $readItems = $this->read($channel, $mage);

        $convertedItems = $this->convert($readItems);

        return $convertedItems;
    }

}