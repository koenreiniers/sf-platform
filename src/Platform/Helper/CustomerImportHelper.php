<?php
namespace Platform\Helper;

use AppBundle\Entity\Channel;
use AppBundle\Entity\Store;
use CrmBundle\Entity\Address;
use CrmBundle\Entity\Customer;
use Doctrine\ORM\EntityManager;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CustomerImportHelper
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

    public function insertCustomerData(Channel $channel, array $items)
    {
        $customers = [];

        $customerRepository = $this->entityManager->getRepository(Customer::class);

        foreach($items as $customerData) {

            $customer = $customerRepository->findOneBy([
                'channel' => $channel,
                'externalId' => $customerData['external_id'],
            ]);

            if($customer === null) {
                $customer = $this->createCustomer($customerData);
                $customer->setChannel($channel);
                $channel->addCustomer($customer);
            }
            $this->updateCustomer($customer, $customerData);

            $customers[] = $customer;
        }


        return $customers;
    }


    private function createCustomer(array $data)
    {
        $customer = new Customer();
        $customer->setExternalId($data['external_id']);
        return $customer;
    }

    private function updateAddress(Address $address, array $data)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $props = ['first_name', 'last_name', 'external_id', 'street', 'city', 'company'];

        foreach($props as $prop) {
            if(!array_key_exists($prop, $data)) {
                continue;
            }
            $accessor->setValue($address, $prop, $data[$prop]);
        }
    }

    private function updateCustomer(Customer $customer, array $data)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $props = ['external_id', 'first_name', 'last_name', 'middle_name', 'name_prefix', 'name_suffix', 'external_created_at', 'external_updated_at', 'email'];

        foreach($props as $prop) {
            if(!array_key_exists($prop, $data)) {
                continue;
            }
            $accessor->setValue($customer, $prop, $data[$prop]);
        }

        foreach($data['addresses'] as $addressData) {

            $address = $customer->getAddresses()->filter(function($v) use($addressData) {
                return $v->getExternalId() === $addressData['external_id'];
            })->first();

            if(!$address) {
                $address = new Address();
                $address->setChannel($customer->getChannel());
                $customer->addAddress($address);
                $address->setCustomer($customer);
            }
            $this->updateAddress($address, $addressData);
        }

        return;
    }
}