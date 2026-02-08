<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Article Repository
 *
 * Single Responsibility: Only handles data access queries
 * - No business logic here (that's the service's job)
 * - No persistence operations (persist/flush - that's the service's job)
 * - Only SELECT queries and query building
 *
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Find all articles ordered by newest first
     *
     * @return Article[]
     */
    public function findAllOrderedByNewest(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Search articles by title or description
     *
     * @return Article[]
     */
    public function search(string $query): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.title LIKE :query')
            ->orWhere('a.description LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
