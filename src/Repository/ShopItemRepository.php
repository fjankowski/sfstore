<?php

namespace App\Repository;

use App\Entity\ShopItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShopItem>
 *
 * @method ShopItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopItem[]    findAll()
 * @method ShopItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopItem::class);
    }

    public function findByCategory(int $id)
    {
        $qb = $this->createQueryBuilder('i');
        $qb->select('i.title')
            ->addSelect('p.id AS title_id')
            ->addSelect('c.name')
            ->innerJoin('i.category', 'c')
            ->where('i.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return ShopItem[] Returns an array of ShopItem objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ShopItem
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
