<?php

namespace App\Repository;

use App\Entity\RevenueCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RevenueCategory>
 */
class RevenueCategoryRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, RevenueCategory::class);
	}

	//    /**
	//     * @return RevenueCategory[] Returns an array of RevenueCategory objects
	//     */
	//    public function findByExampleField($value): array
	//    {
	//        return $this->createQueryBuilder('r')
	//            ->andWhere('r.exampleField = :val')
	//            ->setParameter('val', $value)
	//            ->orderBy('r.id', 'ASC')
	//            ->setMaxResults(10)
	//            ->getQuery()
	//            ->getResult()
	//        ;
	//    }

	//    public function findOneBySomeField($value): ?RevenueCategory
	//    {
	//        return $this->createQueryBuilder('r')
	//            ->andWhere('r.exampleField = :val')
	//            ->setParameter('val', $value)
	//            ->getQuery()
	//            ->getOneOrNullResult()
	//        ;
	//    }
}
