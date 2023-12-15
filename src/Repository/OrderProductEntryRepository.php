<?php

namespace App\Repository;

use App\Entity\OrderProductEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderProductEntry>
 *
 * @method OrderProductEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderProductEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderProductEntry[]    findAll()
 * @method OrderProductEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderProductEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderProductEntry::class);
    }

//    /**
//     * @return OrderProductEntry[] Returns an array of OrderProductEntry objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OrderProductEntry
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
