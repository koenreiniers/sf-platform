<?php
namespace Raw\Bundle\UserBundle\Controller;


use Raw\Bundle\PlatformBundle\Controller\AdvancedController;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AdvancedController
{

    public function logoutAction(Request $request)
    {
        throw new \Exception('Your firewall is wrongly configured');
    }

    public function loginAction(Request $request)
    {
        $utils = $this->get('security.authentication_utils');

        $lastError = $utils->getLastAuthenticationError();

        $lastUsername = $utils->getLastUsername();


        return $this->render('RawUserBundle:Security:login.html.twig', [
            'error' => $lastError,
            'lastUsername' => $lastUsername,
        ]);
    }
}