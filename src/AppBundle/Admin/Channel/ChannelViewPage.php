<?php
namespace AppBundle\Admin\Channel;

use Platform\PlatformHelper;
use Raw\Component\Admin\Easy\Element\LayoutElement;
use Raw\Component\Admin\Page\AdminPage;
use SalesBundle\Statistics\OrderStatistics;

class ChannelViewPage extends AdminPage
{
    /**
     * @var PlatformHelper
     */
    private $platformHelper;

    /**
     * @var OrderStatistics
     */
    private $orderStats;

    /**
     * ChannelViewPage constructor.
     * @param PlatformHelper $platformHelper
     * @param OrderStatistics $orderStats
     */
    public function __construct(PlatformHelper $platformHelper, OrderStatistics $orderStats)
    {
        $this->platformHelper = $platformHelper;
        $this->orderStats = $orderStats;
    }

    public function buildLayout(LayoutElement $layout, $entity, array $options)
    {
        $channel = $entity;

        $adapter = $this->platformHelper->getAdapter($channel);

        $orderAmountPerState = $this->orderStats->getOrderAmountPerState([
            'channel' => $channel,
        ]);

        $layout
            ->template('AppBundle:Channel:view/content.html.twig', [
                'channel' => $channel,
                'authorized' => $adapter->isAuthorized($channel),
                'orderAmountPerState' => $orderAmountPerState,
            ])
        ;
    }
}