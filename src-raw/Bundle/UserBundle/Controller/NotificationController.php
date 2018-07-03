<?php
namespace Raw\Bundle\UserBundle\Controller;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Raw\Bundle\ApiBundle\Envelope\CollectionEnvelope;
use Raw\Bundle\ApiBundle\Envelope\Data\LazyData;
use Raw\Bundle\PlatformBundle\Controller\AdvancedController;
use Raw\Bundle\UserBundle\Entity\Notification;
use Raw\Bundle\UserBundle\Form\Type\NotificationType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NotificationController extends AdvancedController
{

    protected function getClassName()
    {
        return Notification::class;
    }

    public function gridAction(Request $request)
    {
        return $this->render('RawUserBundle:Notification:grid.html.twig');
    }

    protected function qbHandleRequest(QueryBuilder $qb, Request $request)
    {
        if($request->get('limit')) {
            $qb->setMaxResults($request->get('limit'));
        }
    }

    public function indexAction(Request $request)
    {
        $qb = $this->createQueryBuilder('notification')
            ->andWhere('notification.owner = :user')
            ->orderBy('notification.createdAt', 'DESC')
            ->setParameters([
                'user' => $this->getUser(),
            ]);

        $this->qbHandleRequest($qb, $request);

        $notifications = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);

        return $notifications;
    }

    public function unreadAction(Request $request)
    {

        $qb = $this->createQueryBuilder('notification')
            ->where('notification.read = false')
            ->andWhere('notification.owner = :user')
            ->orderBy('notification.createdAt', 'DESC')
            ->setParameters([
                'user' => $this->getUser(),
            ]);

        $this->qbHandleRequest($qb, $request);

        $items = new LazyData(function() use($qb) {
            $notifications = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
            return $notifications;
        });

        $count = new LazyData(function() use($qb) {
            $countQb = clone $qb;
            $countQb
                ->select('COUNT(notification)')
                ->setMaxResults(null)
            ;
            return $countQb->getQuery()->getSingleScalarResult();
        });

        $envelope = new CollectionEnvelope($items, $count);

        return $envelope;
    }

    public function deleteAction(Request $request, $id)
    {
        /** @var Notification $notification */
        $notification = $this->findOr404($id);

        $this->denyAccessUnlessGranted('delete', $notification);

        $this->entityManager->remove($notification);
        $this->entityManager->flush();

    }

    public function viewAction(Request $request, $id)
    {
        /** @var Notification $notification */
        $notification = $this->findOr404($id);

        $this->denyAccessUnlessGranted('view', $notification);

        if(!$notification->isRead()) {
            $notification->setRead(true);
            $this->entityManager->flush();
        }

        $notification = $this->get('serializer')->normalize($notification);

        return $notification;
    }



    public function createAction(Request $request)
    {
        $formType = NotificationType::class;

        $form = $this->createForm($formType);

        $this->formHandleRequest($form, $request);

        if($form->isValid()) {
            $entity = $form->getData();
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return new JsonResponse($entity->getId());
        }
        $errors = $this->get('serializer')->normalize($form->getErrors(true));
        throw $this->createBadRequestException($errors);
    }
}