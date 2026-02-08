<?php

namespace App\Security\Voter;

use App\Entity\Article;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Article Voter - Handles authorization for Article operations
 *
 * Single Responsibility Principle:
 * - Only responsible for Article-related authorization decisions
 * - Each permission check is isolated in its own method
 *
 * Open/Closed Principle:
 * - Easy to extend with new permissions without modifying existing code
 * - New permissions can be added by adding new constants and methods
 *
 * Liskov Substitution Principle:
 * - Properly extends Symfony's Voter base class
 * - Can be substituted anywhere a Voter is expected
 *
 * Interface Segregation Principle:
 * - Implements only the required voter methods
 * - No unnecessary dependencies
 *
 * Dependency Inversion Principle:
 * - Depends on Symfony's abstractions (TokenInterface, UserInterface)
 * - Controllers depend on this voter through the security system (is_granted)
 */
class ArticleVoter extends Voter
{
    // Permission constants - centralized and type-safe
    public const DELETE = 'ARTICLE_DELETE';
    public const EDIT = 'ARTICLE_EDIT';
    public const VIEW = 'ARTICLE_VIEW';

    /**
     * Determines if this voter supports the given attribute and subject
     *
     * @param string $attribute The permission being checked
     * @param mixed $subject The object being checked against
     * @return bool
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        // Only vote on Article objects with our defined attributes
        return in_array($attribute, [self::DELETE, self::EDIT, self::VIEW])
            && $subject instanceof Article;
    }

    /**
     * Performs the actual authorization check
     *
     * @param string $attribute The permission being checked
     * @param mixed $subject The Article being checked
     * @param TokenInterface $token The security token containing user information
     * @param Vote|null $vote The vote object (optional, added in Symfony 8)
     * @return bool True if access is granted, false otherwise
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {


    $user = $token->getUser();

        // User must be logged in
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Article $article */
        $article = $subject;

        // Delegate to specific permission methods (Single Responsibility)
        return match($attribute) {
            self::VIEW => $this->canView($article, $user),
            self::EDIT => $this->canEdit($article, $user),
            self::DELETE => $this->canDelete($article, $user),
            default => false,
        };
    }

    /**
     * Check if user can view the article
     *
     * @param Article $article
     * @param UserInterface $user
     * @return bool
     */
    private function canView(Article $article, UserInterface $user): bool
    {
        // Any authenticated admin can view articles
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

    /**
     * Check if user can edit the article
     *
     * @param Article $article
     * @param UserInterface $user
     * @return bool
     */
    private function canEdit(Article $article, UserInterface $user): bool
    {
        // Any admin can edit articles
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

    /**
     * Check if user can delete the article
     *
     * Business Rule: Only SUPER_ADMIN role can delete articles
     *
     * @param Article $article
     * @param UserInterface $user
     * @return bool
     */
    private function canDelete(Article $article, UserInterface $user): bool
    {
        // Only SUPER_ADMIN can delete articles
        return in_array('ROLE_SUPER_ADMIN', $user->getRoles());
    }
}
