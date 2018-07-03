<?php
namespace CrmBundle\Admin\Action;

use Raw\Component\Admin\ResolvedAdmin;
use Symfony\Component\HttpFoundation\Request;

class CustomerShowAction
{
    public function handle(Request $request, $entity)
    {
        $customers = [];
        return [
            'customers' => $customers,
        ];
    }
}