<?php
namespace Raw\Bundle\UserBundle\Statistics;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Raw\Bundle\UserBundle\Entity\User;
use Raw\Component\Statistics\Dataset\CallbackDataset;
use Raw\Component\Statistics\Dataset\Dataset;
use Raw\Component\Statistics\Dataset\DoctrineDataset;
use Raw\Component\Statistics\Statistic;
use Raw\Component\Statistics\CallbackStatistic;
use Raw\Component\Statistics\StatisticCollection;
use Raw\Component\Statistics\StatisticLoaderInterface;
use Symfony\Component\VarDumper\VarDumper;

class UserDatasetLoader implements StatisticLoaderInterface
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

    public function load(StatisticCollection $datasets)
    {

        $dataset = new DoctrineDataset(function(DoctrineDataset $dataset, array $parameters){
            $qb = $this->entityManager->createQueryBuilder()
                ->from(User::class, 'user')
                ->select('COUNT(user) AS datum');
            $dataset->setQueryBuilder($qb);
        });
        $dataset->groupBy('user.createdAt', 'date');

        $datasets->add('new_users', $dataset);

        $dataset = new DoctrineDataset(function(DoctrineDataset $dataset, array $parameters){
            $qb = $this->entityManager->createQueryBuilder()
                ->from(User::class, 'user')
                ->select('(COUNT(user) + 3) AS datum');
            $dataset->setQueryBuilder($qb);
        });
        $dataset->groupBy('user.createdAt', 'date');

        $datasets->add('random_data', $dataset);

    }
}