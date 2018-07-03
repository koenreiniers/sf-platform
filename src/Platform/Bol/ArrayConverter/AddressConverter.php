<?php
namespace Platform\Bol\ArrayConverter;

use Platform\Magento\ArrayConverter\DefaultArrayConverter;

class AddressConverter extends DefaultArrayConverter
{
    public function __construct()
    {
        $map = [
            'first_name' => 'Firstname',
            'last_name' => 'Surname',
            'street' => 'Streetname',
            'city' => 'City',
            'company' => 'Company',
        ];
        parent::__construct($map);
    }
}