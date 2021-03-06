<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BlogRepository extends ServiceEntityRepository
{
    /**
     * The blog repository constructor
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findRandomArticles()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT id, title, description, author_id, image, created_at FROM blog ORDER BY RAND() LIMIT 3';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();

    }

    /**
     * Find blog by category
     * @param   int     $id         The category id
     * @return mixed
     */
    public function findByCategory(int $id)
    {
        return $this->createQueryBuilder('b')
            ->where('b.category = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find the three last articles
     * @return mixed
     */
    public function findByThreeLast()
    {
      return $this->createQueryBuilder('b')
            ->setMaxResults(3)
            ->orderBy('b.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}