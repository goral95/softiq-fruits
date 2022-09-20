<?php

namespace App\Repository;

use App\Entity\FruitInSalad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FruitInSalad>
 *
 * @method FruitInSalad|null find($id, $lockMode = null, $lockVersion = null)
 * @method FruitInSalad|null findOneBy(array $criteria, array $orderBy = null)
 * @method FruitInSalad[]    findAll()
 * @method FruitInSalad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitInSaladRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FruitInSalad::class);
    }

    public function add(FruitInSalad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FruitInSalad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FruitInSalad[] Returns an array of FruitInSalad objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FruitInSalad
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
