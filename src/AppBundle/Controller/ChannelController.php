<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Channel;
use AppBundle\Form\Type\ChannelActionsType;
use AppBundle\Form\Type\DeleteType;
use CrmBundle\Entity\Customer;
use Doctrine\ORM\EntityManager;
use Platform\Magento\Importer\CustomerImporter;
use Platform\PlatformAdapterInterface;
use AppBundle\Form\Type\ChannelType;
use SalesBundle\Entity\Shipment;
use SalesBundle\Entity\ShipmentTrack;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Template
 * @Route("/channels")
 */
class ChannelController extends Controller
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
     * @return Channel
     */
    private function findChannelOr404($id)
    {
        $entity = $this->getDoctrine()->getManager()->getRepository(Channel::class)->find($id);

        if($entity === null) {
            throw $this->createNotFoundException();
        }

        return $entity;
    }

    /**
     * @Route("/load-form", name="app.channel.load_form")
     */
    public function loadFormAction(Request $request)
    {
        $channel = null;//new Channel();
        $form = $this->createForm(ChannelType::class, $channel);
        $form->handleRequest($request);

        $content = $this->get('twig')->render('AppBundle:Channel/partial:form.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($content);
    }

    /**
     * @Route("/create", name="app.channel.create")
     */
    public function createAction(Request $request)
    {
        $channel = new Channel();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ChannelType::class, $channel);
        $form->handleRequest($request);

        if($form->isValid()) {

            $this->get('app.platform_helper')->fillDefaultParameters($channel);

            $em->persist($channel);
            $em->flush();

            $this->addFlash('success', 'Channel has been created');

            return $this->redirectToRoute('app.channel.edit', [
                'id' => $channel->getId(),
            ]);


        }

        return [
            'channel' => $channel,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/edit", name="app.channel.edit")
     */
    public function editAction(Request $request, $id)
    {
        $channel = $this->findChannelOr404($id);

        $form = $this->createForm(ChannelType::class, $channel, [
            'reloadable' => false,
        ]);
        $form->handleRequest($request);
        if($form->isValid()) {

            $this->entityManager->flush();

            $this->addFlash('success', 'Channel has been saved');

            return $this->redirectToRoute('app.channel.view', [
                'id' => $channel->getId(),
            ]);
        }

        return $this->render('AppBundle:Channel:create.html.twig', [
            'channel' => $channel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app.channel.view")
     */
    public function viewAction(Request $request, $id)
    {
        $channel = $this->findChannelOr404($id);

        $adapter = $this->getAdapter($channel);

        $orderStats = $this->get('sales.statistics.order');

        $orderAmountPerState = $orderStats->getOrderAmountPerState([
            'channel' => $channel,
        ]);



        return [
            'channel' => $channel,
            'authorized' => $adapter->isAuthorized($channel),
            'orderAmountPerState' => $orderAmountPerState,
        ];
    }

    public function getAdapter(Channel $channel)
    {
        return $this->get('app.platform_helper')->getAdapter($channel);
    }
    /**
     * @Route("/{id}/delete", name="app.channel.delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $channel = $this->findChannelOr404($id);
        $form = $this->createForm(DeleteType::class, $channel);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($channel);
            $em->flush();

            $this->addFlash('success', 'Channel removed');

            return $this->redirectToRoute('app.channel.index');
        }

        return [
            'channel' => $channel,
            'form' => $form->createView(),
        ];




    }

    /**
     * @Route("/{id}/authorize", name="app.channel.authorize")
     */
    public function authorizeAction(Request $request, $id)
    {
        $channel = $this->findChannelOr404($id);

        $adapter = $this->getAdapter($channel);

        try {
            $adapter->startAuthorization($channel);
        } catch(\Exception $e) {
            $this->addFlash('warning', sprintf('Authorization failed. Error message: %s', $e->getMessage()));
        }
        #if(!$adapter->isAuthorized($channel)) {

        #}


        return $this->redirectToRoute('app.channel.view', [
            'id' => $id,
        ]);
    }



    /**
     * @Route("", name="app.channel.index")
     */
    public function indexAction(Request $request)
    {
        $channels = $this->getDoctrine()->getRepository(Channel::class)->findAll();

        return [
            'channels' => $channels,
        ];
    }

    /**
     * @Route("/{id}/execute/{actionName}", name="app.channel.execute")
     */
    public function executeAction(Request $request, $id, $actionName)
    {
        $em = $this->getDoctrine()->getManager();
        $channel = $this->findChannelOr404($id);

        $results = $this->get('app.platform_helper')->executeAction($channel, $actionName);

        if(is_array($results)) {
            foreach($results as $result) {
                $em->persist($result);
            }
        }

        $em->flush();

        $this->addFlash('success', 'Action executed');

        return $this->redirectToRoute('app.channel.view', [
            'id' => $id,
        ]);

    }

}
