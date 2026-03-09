<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Promotion;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Promotion>
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }

    public function findValidForProduct(Product $product, DateTimeInterface $requestDate)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.productPromotions', 'pp')
            ->andWhere('pp.product = :product')
            ->andWhere('pp.validTo >= :requestDate OR pp.validTo IS NULL')
            ->setParameter('product', $product)
            ->setParameter('requestDate', $requestDate)
            ->getQuery()
            ->getResult();
    }

}
