<?php
namespace Raw\Bundle\UserBundle\Statistics;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Raw\Bundle\UserBundle\Entity\User;
use Raw\Component\Statistics\Dataset\CallbackDataset;
use Raw\Component\Statistics\Statistic;
use Raw\Component\Statistics\CallbackStatistic;
use Raw\Component\Statistics\StatisticCollection;
use Raw\Component\Statistics\StatisticLoaderInterface;
use Symfony\Component\VarDumper\VarDumper;

class UserStatisticLoader implements StatisticLoaderInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UserStatisticsProvider constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getNewUsersBetween(\DateTime $start = null, \DateTime $end = null)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->from(User::class, 'user')
            ->select('COUNT(user)');

        if($end !== null) {
            $qb
                ->andWhere('user.createdAt <= :end')
                ->setParameter('end', $end);
        }
        if($start !== null) {
            $qb
                ->setParameter('start', $start)
                ->andWhere('user.createdAt >= :start');
        }

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    public function getAmountOfUsers(array $parameters)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->from(User::class, 'user')
            ->select('COUNT(user)');
        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    public function load(StatisticCollection $statistics)
    {
        $statistics->add('amount_of_users', new CallbackStatistic([$this, 'getAmountOfUsers']));
        $statistics->add('visitors_report', new Statistic([
            'countries' => [
                'NL' => 10,
            ],
            'visits' => 8390,
            'referrals' => '30%',
            'organic' => '70%',
        ]));
        $statistics->add('users_registered_today', new CallbackStatistic(function(array $parameters){
            $midnight = $parameters['start'];
            $now = $parameters['end'];
            $v = $this->getNewUsersBetween($midnight, $now);

            return $v;
        }));
    }
}