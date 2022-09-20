<?php

namespace App\Repository;

use App\Entity\FruitSaladRecipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FruitSaladRecipe>
 *
 * @method FruitSaladRecipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method FruitSaladRecipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method FruitSaladRecipe[]    findAll()
 * @method FruitSaladRecipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitSaladRecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FruitSaladRecipe::class);
    }

    public function add(FruitSaladRecipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FruitSaladRecipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FruitSaladRecipe[] Returns an array of FruitSaladRecipe objects
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

//    public function findOneBySomeField($value): ?FruitSaladRecipe
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
