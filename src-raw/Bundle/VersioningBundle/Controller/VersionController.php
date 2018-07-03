<?php
namespace Raw\Bundle\VersioningBundle\Controller;

use Doctrine\ORM\Query;
use Raw\Bundle\VersioningBundle\Entity\Version;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class VersionController extends Controller
{
    public function restoreAction(Request $request, $id)
    {
        $version = $this->getDoctrine()->getRepository(Version::class)->find($id);

        $manager = $this->get('raw_versioning.manager');

        $manager->restore($version);

        $this->addFlash('success', 'Version has been restored');

        return $this->redirect($request->headers->get('referer'));
    }
}