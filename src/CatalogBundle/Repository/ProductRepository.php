<?php
namespace CatalogBundle\Repository;

use AppBundle\Entity\Channel;
use CatalogBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{

    /**
     * @param Channel $channel
     * @return Product|null
     */
    public function findLastUpdatedProduct(Channel $channel)
    {
        $lastUpdatedProduct = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.channel = :channel')
            ->setMaxResults(1)
            ->orderBy('p.externalUpdatedAt', 'DESC')
            ->setParameters([
                'channel' => $channel,
            ])->getQuery()->getOneOrNullResult()
        ;
        return $lastUpdatedProduct;
    }
}