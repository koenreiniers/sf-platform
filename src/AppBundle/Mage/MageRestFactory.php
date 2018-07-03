<?php
namespace AppBundle\Mage;

use AppBundle\Entity\Channel;
use AppBundle\Mage\Storage\ChannelSpecificStorage;
use Raw\Component\Mage\MageRestFactory as BaseFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class MageRestFactory extends BaseFactory
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * MageRestFactory constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Channel $channel
     * @return \Raw\Component\Mage\MageRestFacade
     */
    public function createForChannel(Channel $channel)
    {
        $storage = new ChannelSpecificStorage($channel);

        $callbackUrl = $this->router->generate('mage_callback', [
            'channelId' => $channel->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $mageRest = $this->create($channel->getParameter('url'), $channel->getParameter('consumer_key'), $channel->getParameter('consumer_secret'), $callbackUrl, $storage);

        return $mageRest;
    }
}