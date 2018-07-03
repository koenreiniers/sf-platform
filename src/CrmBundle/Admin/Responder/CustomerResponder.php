<?php
namespace CrmBundle\Admin\Responder;

use CrmBundle\Entity\Customer;
use CrmBundle\Form\Type\CustomerType;
use Raw\Component\Admin\Easy\Element\FormElement;
use Raw\Component\Admin\ResolvedAdmin;

class CustomerResponder
{

    public function create(ResolvedAdmin $admin, Customer $customer)
    {
        /** @var FormElement $layout */
        $layout = $admin->createEmptyLayout(FormElement::class);

        $layout
            ->setFormType(CustomerType::class)
            ->addTabset()
                ->addTab('General')
                    ->addField('firstName')
                ->endTab()
            ->endTabset()
            ;

        return $admin->createResponse($layout);
    }

    public function index()
    {
        $customers = [];
        return json_encode($customers);
    }
}