<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Service\Article\ArticleServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Article CRUD Controller
 *
 * Single Responsibility: Only handles HTTP request/response for articles
 * - Delegates business logic to ArticleService
 * - Delegates form handling to FormFactory
 * - Delegates rendering to Twig
 * - Thin controller, fat service
 *
 * Dependency Inversion: Depends on ArticleServiceInterface, not concrete implementation
 * - Easy to test with mock service
 * - Easy to swap service implementation
 */
#[Route('/admin/articles')]
#[IsGranted('ROLE_ADMIN')]
class ArticleController extends AbstractController
{
    public function __construct(
        private ArticleServiceInterface $articleService
    ) {
    }

    /**
     * List all articles
     */
    #[Route('', name: 'admin_article_index', methods: ['GET'])]
    public function index(): Response
    {
        $articles = $this->articleService->findAll();

        return $this->render('admin/article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * Show article details
     */
    #[Route('/{id}', name: 'admin_article_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        try {
            $article = $this->articleService->findById($id);
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('admin/article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * Create new article
     */
    #[Route('/new', name: 'admin_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->articleService->create($article);

                $this->addFlash('success', 'Article created successfully!');

                return $this->redirectToRoute('admin_article_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error creating article: ' . $e->getMessage());
            }
        }

        return $this->render('admin/article/new.html.twig', [
            'form' => $form,
            'article' => $article,
        ]);
    }

    /**
     * Edit existing article
     */
    #[Route('/{id}/edit', name: 'admin_article_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id): Response
    {
        try {
            $article = $this->articleService->findById($id);
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('admin_article_index');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->articleService->update($article);

                $this->addFlash('success', 'Article updated successfully!');

                return $this->redirectToRoute('admin_article_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error updating article: ' . $e->getMessage());
            }
        }

        return $this->render('admin/article/edit.html.twig', [
            'form' => $form,
            'article' => $article,
        ]);
    }

    /**
     * Delete article
     * 
     * Authorization: Uses ArticleVoter to check ARTICLE_DELETE permission
     * Business Rule: Only SUPER_ADMIN role can delete articles
     */
    #[Route('/{id}/delete', name: 'admin_article_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, int $id): Response
    {
        // CSRF protection
        if (!$this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token');
            return $this->redirectToRoute('admin_article_index');
        }

        try {
            $article = $this->articleService->findById($id);
            
            // Authorization check using Voter (Dependency Inversion Principle)
            // Controller depends on is_granted abstraction, not concrete authorization logic
            $this->denyAccessUnlessGranted('ARTICLE_DELETE', $article);
            
            $this->articleService->delete($article);

            $this->addFlash('success', 'Article deleted successfully!');
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('error', $e->getMessage());
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error deleting article: ' . $e->getMessage());
        }

        return $this->redirectToRoute('admin_article_index');
    }
}
