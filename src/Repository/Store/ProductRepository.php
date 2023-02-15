<?php

namespace App\Repository\Store;

use App\Entity\Store\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
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
     * @return Product[]
     */
    public function findAllWithImages() : array
    {
        return $this
            ->createQueryBuilder('p')
            ->addSelect('i')
            ->innerJoin('p.image', 'i')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[]
     */
    public function findBrandWithImages(array $brand) : array
    {
        return $this
            ->createQueryBuilder('p')
            ->addSelect('i')
            ->innerJoin('p.image', 'i')
            ->where('p.brand = :brand')
            ->setParameter('brand', $brand)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product
     * @throws NonUniqueResultException
     */
    public function findOneByIdWithImage(int $id) : Product
    {
        return $this
            ->createQueryBuilder('p')
            ->addSelect('i')
            ->innerJoin('p.image', 'i')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Product[]
     */
    public function findLastProducts(): array
    {
        $results = $this->getEntityManager()->createQueryBuilder();
        return $results
            ->select('p')
            ->from(Product::class, 'p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    public function getMostPopularProducts() : array
    {
        return $this
            ->createQueryBuilder('p')
            ->innerJoin('p.comments', 'c')
            ->groupBy('p')
            ->orderBy('COUNT(c.id)', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
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
