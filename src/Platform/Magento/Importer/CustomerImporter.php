<?php
namespace Platform\Magento\Importer;

use AppBundle\Entity\Channel;
use AppBundle\Entity\Store;
use CrmBundle\Entity\Address;
use CrmBundle\Entity\Customer;
use Doctrine\ORM\EntityRepository;
use Platform\Magento\ArrayConverter\DefaultArrayConverter;
use Raw\Component\Mage\MageRestFacade;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\VarDumper\VarDumper;

class CustomerImporter
{

    private function map(array $map, array $data)
    {
        $mapped = [];
        foreach($map as $to => $from) {
            $value = null;
            if(is_string($from)) {
                if(isset($data[$from])) {
                    $value = $data[$from];
                }
            } else {
                $value = $from($data);
            }
            $mapped[$to] = $value;
        }
        return $mapped;
    }

    public function getConverter()
    {
        $map = [
            'external_id' => 'entity_id',
            'first_name' => 'firstname',
            'last_name' => 'lastname',
            'middle_name' => 'middlename',
            'name_prefix' => 'prefix',
            'name_suffix' => 'suffix',
            'external_created_at' => function($d) {
                return new \DateTime($d['created_at']);
            },
            'external_updated_at' => function($d) {
                return new \DateTime($d['updated_at']);
            },
            'addresses' => function($d) {

                $addresses = [];

                foreach($d['addresses'] as $addressData) {
                    $addressData = $this->map([
                        'external_id' => 'entity_id',
                        'first_name' => 'firstname',
                        'last_name' => 'lastname',
                        'external_created_at' => function($d) {
                            return new \DateTime($d['created_at']);
                        },
                        'external_updated_at' => function($d) {
                            return new \DateTime($d['updated_at']);
                        },
                    ], $addressData);

                    $addresses[] = $addressData;
                }

                return $addresses;
            },
        ];
        return new DefaultArrayConverter($map);
    }

    public function read(Channel $channel, MageRestFacade $mage, \DateTime $after = null)
    {
        $options = $mage->opt();

        if($after !== null) {
            $options->addFilter('updated_at', 'gt', $after->format('Y-m-d H:i:s'));
        }

        return $mage->getCustomers($options);
    }

    public function convert(array $items)
    {
        $converted = $this->getConverter()->convertAll($items);
        return $converted;
    }



    public function import(Channel $channel, MageRestFacade $mage, \DateTime $after = null)
    {
        $readItems = $this->read($channel, $mage, $after);

        $convertedItems = $this->convert($readItems);

        return $convertedItems;
    }


}