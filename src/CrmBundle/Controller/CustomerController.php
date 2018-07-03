<?php

namespace CrmBundle\Controller;

use CrmBundle\Entity\Customer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use SalesBundle\Entity\Order;
use SalesBundle\Entity\OrderItem;
use SalesBundle\Entity\Shipment;
use SalesBundle\Entity\ShipmentItem;
use SalesBundle\Entity\ShipmentTrack;
use SalesBundle\Form\Type\ShipmentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Workflow;

/**
 * @Route("/customers")
 * @Template
 */
class CustomerController extends Controller
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @param int $id
     * @return Customer
     */
    private function findOr404($id)
    {
        $entity = $this->entityManager->getRepository(Customer::class)->find($id);

        if($entity === null) {
            throw $this->createNotFoundException();
        }

        return $entity;
    }

    /**
     * @Route("", name="crm.customer.index")
     */
    public function indexAction(Request $request)
    {
        $customers = $this->entityManager->getRepository(Customer::class)->findAll();

        return [
            'customers' => $customers,
        ];
    }

    /**
     * @Route("/{id}", name="crm.customer.view")
     */
    public function viewAction(Request $request, $id)
    {
        $customer = $this->findOr404($id);

        $stats = $this->get('sales.statistics.order');

        $mostBoughtItems = $stats->getMostBoughtItems([
            'customer' => $customer,
        ], 3);

        $recentlyBoughtItems = $stats->getRecentlyBoughtItems([
            'customer' => $customer,
        ], 3);

        return $this->render('CrmBundle:Customer:view/content.html.twig', [
            'customer' => $customer,
            'mostBoughtItems' => $mostBoughtItems,
            'recentlyBoughtItems' => $recentlyBoughtItems,
        ]);;
    }

}
