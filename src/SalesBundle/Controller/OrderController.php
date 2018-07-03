<?php

namespace SalesBundle\Controller;

use AppBundle\Entity\Channel;
use AppBundle\Entity\Store;
use Doctrine\ORM\QueryBuilder;
use Platform\Magento\Importer\StoreImporter;
use SalesBundle\Entity\Order;
use AppBundle\Form\Type\ChannelType;
use AppBundle\Mage\Storage\ChannelSpecificStorage;
use Platform\Magento\Importer\OrderImporter;
use Platform\Magento\MagentoAdapter;
use Raw\Component\Mage\MageRestFacade;
use AppBundle\Mage\MageRestFactory;
use SalesBundle\Entity\Shipment;
use SalesBundle\Entity\ShipmentItem;
use SalesBundle\Entity\ShipmentTrack;
use SalesBundle\Form\Type\ShipmentType;
use SalesBundle\Workflow\OrderWorkflow;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\MarkingStore\SingleStateMarkingStore;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

/**
 * @Route("/orders")
 * @Template
 */
class OrderController extends Controller
{
    /**
     * @param int $id
     * @return Order
     */
    private function findOr404($id)
    {
        $entity = $this->getDoctrine()->getManager()->getRepository(Order::class)->find($id);

        if($entity === null) {
            throw $this->createNotFoundException();
        }

        return $entity;
    }

    private function createNewShipment(Order $order)
    {
        $newShipment = new Shipment();
        $newShipment->setOrder($order);
        foreach($order->getOrderItems() as $orderItem) {
            $qtyLeft = $orderItem->getQtyLeft();
            if($qtyLeft <= 0) {
                continue;
            }
            $shipmentItem = new ShipmentItem();
            $shipmentItem->setOrderItem($orderItem);
            $shipmentItem->setQty($qtyLeft);
            $shipmentItem->setShipment($newShipment);
            $newShipment->addShipmentItem($shipmentItem);
        }
        $track = new ShipmentTrack();
        $track->setShipment($newShipment);
        $newShipment->addShipmentTrack($track);

        return $newShipment;
    }
    /**
     * @Route("", name="sales.order.index")
     */
    public function indexAction(Request $request)
    {
        $selectedState = $request->query->get('state', Order::STATE_PROCESSING);

        $orders = $this->getDoctrine()->getManager()->getRepository(Order::class)->findBy([
            'state' => $selectedState,
        ], [
            'id' => 'DESC',
        ]);
        return [
            'selectedState' => $selectedState,
            'orders' => $orders,
        ];
    }



    /**
     * @param $entity
     * @return Workflow
     */
    private function getWorkflow($entity)
    {
        return $this->get('workflow.registry')->get($entity);
    }

    protected function can($entity, $transitionName)
    {
        return $this->getWorkflow($entity)->can($entity, $transitionName);
    }

    /**
     * @Route("/{id}/transition/{transitionName}", name="sales.order.transition")
     */
    public function transitionAction(Request $request, $id, $transitionName)
    {
        $order = $this->findOr404($id);

        $response = $this->redirectToRoute('sales.order.view', [
            'id' => $id,
        ]);

        if(!$this->can($order, $transitionName)) {
            $this->addFlash('danger', sprintf('Transition "%s" not available', $transitionName));
            return $response;
        }

        $channel = $order->getStore()->getChannel();

        $mage = $this->get('app.mage_rest_factory')->createForChannel($channel);

        switch($transitionName) {
            case 'cancel':
                $mage->cancelOrder($order->getExternalId());
                $this->addFlash('success', 'Order has been cancelled');
                $this->getWorkflow($order)->apply($order, $transitionName);
                break;
            case 'hold':
                $mage->holdOrder($order->getExternalId());
                $this->addFlash('success', 'Order has been put on hold');
                $this->getWorkflow($order)->apply($order, $transitionName);
                break;
            case 'unhold':
                $mage->unholdOrder($order->getExternalId());
                $this->addFlash('success', 'Order has been reset to its previous state');
                $this->get('app.platform.magento.order_importer')->importOrder($order);
                break;
            default:
                throw new \Exception(sprintf('Unknown transition "%s"', $transitionName));
        }

        $this->getDoctrine()->getManager()->flush();

        return $response;
    }

    /**
     * @Route("/{id}", name="sales.order.view")
     */
    public function viewAction(Request $request, $id)
    {
        $order = $this->findOr404($id);
        $channel = $order->getStore()->getChannel();
        #$mage = $this->get('app.mage_rest_factory')->createForChannel($channel);


        $em = $this->getDoctrine()->getManager();

//        $importer = new StoreImporter();
//
//        $importer->import($this->getDoctrine()->getRepository(Store::class), $channel, $mage);
//
//        $em->flush();die;

        #VarDumper::dump($mage->getOrder($order->getExternalId()));die;

        $context = [
            'channel' => $channel,
            'order' => $order,
        ];

        if($this->can($order, 'ship')) {

            $newShipment = $this->createNewShipment($order);

            if(count($newShipment->getShipmentItems()) === 0) {
                return $context;
            }

            $newShipmentForm = $this->createForm(ShipmentType::class, $newShipment);

            $newShipmentForm->handleRequest($request);

            $context['newShipmentForm'] = $newShipmentForm->createView();

            if($newShipmentForm->isValid()) {

                /** @var Shipment $shipment */
                $shipment = $newShipmentForm->getData();

                $em->persist($shipment);

                foreach($shipment->getShipmentItems() as $shipmentItem) {
                    $orderItem = $shipmentItem->getOrderItem();
                    $orderItem->setQtyShipped($orderItem->getQtyShipped() + $shipmentItem->getQty());

                    if($orderItem->getQtyLeft() < 0) {
                        throw new \Exception('Invalid QTY');
                    }
                }

                $em->flush();

                $this->addFlash('success', 'Shipment created');

                return $this->redirectToRoute('sales.order.view', [
                    'id' => $id,
                ]);

            }
        }

        return $context;
    }

}
