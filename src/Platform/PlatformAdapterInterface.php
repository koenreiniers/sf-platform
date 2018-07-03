<?php
namespace Platform;

use AppBundle\Entity\Channel;
use SalesBundle\Entity\Order;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface PlatformAdapterInterface
{
    /**
     * @param Channel $channel
     * @return Order[]
     */
    public function importOrders(Channel $channel, \DateTime $after = null);

    public function exportShipments(Channel $channel);

    public function importStores(Channel $channel);

    public function importProducts(Channel $channel, \DateTime $after = null);

    public function importCustomers(Channel $channel, \DateTime $after = null);

    public function isAuthorized(Channel $channel);

    public function startAuthorization(Channel $channel);

    /**
     * @return bool
     */
    public function allowPartialShipments();

    public function buildParameterForm(FormBuilderInterface $builder);

    public function configureDefaultParameters(OptionsResolver $resolver);
}