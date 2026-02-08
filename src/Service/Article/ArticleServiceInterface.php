<?php

namespace App\Service\Article;

use App\Entity\Article;

/**
 * Article Service Interface
 *
 * Dependency Inversion Principle: Depend on abstractions, not concretions
 * - Controllers depend on this interface, not the concrete implementation
 * - Easy to swap implementations (e.g., for testing or different storage)
 * - Defines the contract for article business logic
 */
interface ArticleServiceInterface
{
    /**
     * Find an article by ID
     *
     * @throws \InvalidArgumentException if article not found
     */
    public function findById(int $id): Article;

    /**
     * Find all articles, ordered by newest first
     *
     * @return Article[]
     */
    public function findAll(): array;

    /**
     * Create a new article
     */
    public function create(Article $article): void;

    /**
     * Update an existing article
     */
    public function update(Article $article): void;

    /**
     * Delete an article
     */
    public function delete(Article $article): void;
}
