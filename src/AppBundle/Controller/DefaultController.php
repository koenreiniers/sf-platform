<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Channel;
use AppBundle\Mage\Storage\ChannelSpecificStorage;
use Doctrine\ORM\QueryBuilder;
use Platform\Magento\Importer\OrderImporter;
use Raw\Component\Admin\Easy\Element\GridElement;
use Raw\Component\Admin\Easy\Element\LayoutElement;
use Raw\Component\Admin\Layout\Definition\Builder\ArrayNodeDefinition;
use Raw\Component\Layout\LayoutRendererInterface;
use Raw\Component\Mage\MageRestFacade;
use AppBundle\Mage\MageRestFactory;
use SalesBundle\Entity\Order;
use SalesBundle\Entity\Shipment;
use SalesBundle\Entity\ShipmentTrack;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Template
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {




        return [];
    }


    /**
     * @Route("/complete-sync", name="complete_sync")
     */
    public function completeSyncAction(Request $request)
    {
        $channels = $this->getDoctrine()->getRepository(Channel::class)->findAll();

        $this->get('shipping')->processShipmentTracks();

        $platformHelper = $this->get('app.platform_helper');

        foreach($channels as $channel) {
            $platformHelper->executeAction($channel, 'complete_sync');
        }

        $this->addFlash('success', 'Everything synced');

        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
}
