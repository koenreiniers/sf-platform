<?php
namespace Raw\Bundle\UserBundle\Statistics;

use Doctrine\ORM\EntityManager;
use Raw\Bundle\UserBundle\Entity\Notification;
use Raw\Bundle\UserBundle\Entity\User;
use Raw\Component\Statistics\Statistic;
use Raw\Component\Statistics\CallbackStatistic;
use Raw\Component\Statistics\StatisticCollection;
use Raw\Component\Statistics\StatisticLoaderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NotificationStatisticLoader implements StatisticLoaderInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * NotificationStatisticLoader constructor.
     * @param EntityManager $entityManager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManager $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function getAmountOfUnreadNotifications(array $parameters)
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->from(Notification::class, 'notification')
            ->select('COUNT(notification)')
            ->where('notification.read = false')
            ->andWhere('notification.owner = :owner')
            ->setParameter('owner', $this->tokenStorage->getToken()->getUser())
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function load(StatisticCollection $statistics)
    {
        $statistics->add('unread_notifications', new CallbackStatistic([$this, 'getAmountOfUnreadNotifications']));
    }
}