<?php
namespace Platform\Helper;

use AppBundle\Entity\Channel;
use AppBundle\Entity\Store;
use Doctrine\ORM\EntityManager;

class StoreImportHelper
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


    private function createStore(array $data)
    {
        $store = new Store();
        $store->setExternalId($data['external_id']);
        return $store;
    }

    private function updateStore(Store $store, array $data)
    {
        $store->setName($data['name']);
    }

    public function insertStoreData(Channel $channel, array $items)
    {
        $stores = [];
        foreach($items as $storeData) {
            $store = $this->entityManager->getRepository(Store::class)->findOneBy([
                'channel' => $channel,
                'externalId' => $storeData['external_id']
            ]);
            if($store === null) {
                $store = $this->createStore($storeData);
                $store->setChannel($channel);
                $channel->addStore($store);
            }
            $this->updateStore($store, $storeData);
            $stores[] = $store;
        }

        return $stores;
    }
}