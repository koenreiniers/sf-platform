<?php
namespace Raw\Bundle\AdminBundle\Controller;

use Raw\Bundle\PlatformBundle\Controller\AdvancedController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AdvancedController
{
    public function indexAction(Request $request)
    {
        $adminRegistry = $this->get('raw_admin.registry');
        $admins = $adminRegistry->getAdmins();

        return $this->render('RawAdminBundle:Default:index.html.twig', [
            'admins' => $admins,
        ]);
    }
}