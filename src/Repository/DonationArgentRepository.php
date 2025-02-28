<?php

namespace App\Repository;

use App\Entity\DonationArgent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
/**
 * @extends ServiceEntityRepository<DonationArgent>
 */
class DonationArgentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DonationArgent::class);
    }
    public function findAllQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.id', 'DESC'); // Trie les donations par ID décroissant (ou utilisez un autre champ)
    }
}
    //    /**
    //     * @return DonationArgent[] Returns an array of DonationArgent objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DonationArgent
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

