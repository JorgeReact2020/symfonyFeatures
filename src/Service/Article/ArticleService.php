<?php

namespace App\Service\Article;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Article Service Implementation
 *
 * Single Responsibility Principle: Handles only article business logic
 * - Not responsible for HTTP handling (that's the controller's job)
 * - Not responsible for data access (that's the repository's job)
 * - Not responsible for validation (that's the form/entity's job)
 *
 * Open/Closed Principle: Open for extension through interface
 * - Can be extended by creating another implementation
 * - Closed for modification when adding new features via new services
 */
class ArticleService implements ArticleServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ArticleRepository $repository,
        private LoggerInterface $logger
    ) {
    }

    public function findById(int $id): Article
    {
        $article = $this->repository->find($id);

        if (!$article) {
            $this->logger->warning('Article not found', ['id' => $id]);
            throw new \InvalidArgumentException(sprintf('Article with ID %d not found', $id));
        }

        return $article;
    }

    public function findAll(): array
    {
        return $this->repository->findAllOrderedByNewest();
    }

    public function create(Article $article): void
    {
        $this->logger->info('Creating new article', [
            'title' => $article->getTitle(),
        ]);

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        $this->logger->info('Article created successfully', [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
        ]);
    }

    public function update(Article $article): void
    {
        $this->logger->info('Updating article', [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
        ]);

        $this->entityManager->flush();

        $this->logger->info('Article updated successfully', [
            'id' => $article->getId(),
        ]);
    }

    public function delete(Article $article): void
    {
        $this->logger->info('Deleting article', [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
        ]);

        $this->entityManager->remove($article);
        $this->entityManager->flush();

        $this->logger->info('Article deleted successfully');
    }
}
