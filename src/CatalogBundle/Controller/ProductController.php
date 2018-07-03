<?php

namespace CatalogBundle\Controller;

use CatalogBundle\Entity\Product;
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
 * @Route("/products")
 * @Template
 */
class ProductController extends Controller
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
     * @return Product
     */
    private function findOr404($id)
    {
        $entity = $this->entityManager->getRepository(Product::class)->find($id);

        if($entity === null) {
            throw $this->createNotFoundException();
        }

        return $entity;
    }

    /**
     * @Route("", name="catalog.product.index")
     */
    public function indexAction(Request $request)
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return [
            'products' => $products,
        ];
    }

    /**
     * @Route("/{id}", name="catalog.product.view")
     */
    public function viewAction(Request $request, $id)
    {
        $product = $this->findOr404($id);



        return [
            'product' => $product,
        ];
    }

}
