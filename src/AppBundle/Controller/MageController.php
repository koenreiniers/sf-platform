<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Channel;
use AppBundle\Mage\Storage\ChannelSpecificStorage;
use Platform\Magento\Importer\OrderImporter;
use Raw\Component\Mage\MageRestFacade;
use AppBundle\Mage\MageRestFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\VarDumper\VarDumper;

class MageController extends Controller
{
    private function getChannel($id = 1)
    {

        return $this->getDoctrine()->getManager()->getRepository(Channel::class)->find($id);
        $url = 'http://192.168.1.208/magento';
        $consumerKey = '22e824f60af656db1ba47f27953f7c36';
        $consumerSecret = '506a1883a589e7617ced68ab39222e8d';


        $channel = new Channel();
        $channel->setId($id);

        $channel->setPlatformName('magento');
        $channel->setParameters([
            'url' => $url,
            'consumer_key' => $consumerKey,
            'consumer_secret' => $consumerSecret,
        ]);

        $this->getDoctrine()->getManager()->persist($channel);
        die;

        return $channel;
    }

    /**
     * @param Channel $channel
     * @return \Raw\Component\Mage\MageRestFacade
     */
    private function createMageRest(Channel $channel)
    {
        $factory = $this->get('app.mage_rest_factory');

        $mageRest = $factory->createForChannel($channel);

        return $mageRest;
    }

    /**
     * @Route("/mage/callback/{channelId}", name="mage_callback")
     */
    public function callbackAction(Request $request, $channelId)
    {
        $channel = $this->getChannel($channelId);

        $mageRest = $this->createMageRest($channel);

        $mageRest->getClient()->acquireAccessToken();

        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('Channel "%s" has been successfully authorized', $channel->getName()));

        return $this->redirectToRoute('app.channel.view', [
            'id' => $channelId,
        ]);
    }

    /**
     * @Route("/mage/init/{channelId}", name="mage_init")
     */
    public function initAction(Request $request, $channelId)
    {
        $channel = $this->getChannel($channelId);

        $mageRest = $this->createMageRest($channel);

        $mageRest->getClient()->acquireRequestToken();

        $this->getDoctrine()->getManager()->flush();

        $mageRest->getClient()->redirectForAuthorization();

    }
}
