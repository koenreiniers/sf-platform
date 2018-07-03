<?php
namespace Platform;

use AppBundle\Entity\Channel;
use SalesBundle\Entity\Order;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractPlatformAdapter implements PlatformAdapterInterface
{
    public function importOrders(Channel $channel, \DateTime $after = null)
    {
        throw new \Exception('Not supported');
    }

    public function exportShipments(Channel $channel)
    {
        throw new \Exception('Not supported');
    }

    public function importStores(Channel $channel)
    {
        throw new \Exception('Not supported');
    }

    public function importProducts(Channel $channel, \DateTime $after = null)
    {
        throw new \Exception('Not supported');
    }

    public function importCustomers(Channel $channel, \DateTime $after = null)
    {
        throw new \Exception('Not supported');
    }

    public function isAuthorized(Channel $channel)
    {
        throw new \Exception('Not supported');
    }

    public function startAuthorization(Channel $channel)
    {
        throw new \Exception('Not supported');
    }

    public function allowPartialShipments()
    {
        return false;
    }

    public function buildParameterForm(FormBuilderInterface $builder)
    {

    }

    public function configureDefaultParameters(OptionsResolver $resolver)
    {

    }
}