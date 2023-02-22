<?php

namespace App\Repository\Store;

use App\Entity\Store\Brand;
use App\Entity\Store\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<Product>
     */
    public function find4LastProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->addSelect('i')
            ->leftJoin('p.image', 'i')
            ->orderBy('p.created_at', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return array<Product>
     */
    public function find4PopularProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->addSelect('i')
            ->leftJoin('p.image', 'i')
            ->innerJoin('p.comments', 'c')
            ->groupBy('p.id')
            ->orderBy('COUNT(c.id)', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<Product>
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('p')
            ->addSelect('i')
            ->leftJoin('p.image', 'i')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<Product>
     */
    public function findByBrand(Brand $brand): array
    {
        return $this->createQueryBuilder('p')
            ->addSelect('i')
            ->leftJoin('p.image', 'i')
            ->where('p.brand = :brand')
            ->setParameter('brand', $brand)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ?Product
     */
    public function findOneById(string $id): ?Product
    {
        return $this->createQueryBuilder('p')
            ->addSelect('i')
            ->leftJoin('p.image', 'i')
            ->addSelect('c')
            ->leftJoin('p.colors', 'c')
            ->addSelect('com')
            ->leftJoin('p.comments', 'com')
            ->orderBy('com.created_at', 'DESC')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
