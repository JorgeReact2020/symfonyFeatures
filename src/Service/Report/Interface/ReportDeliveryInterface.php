<?php

declare(strict_types=1);

namespace App\Service\Report\Interface;

/**
 * Interface pour les modes de livraison de rapport
 *
 * SOLID Principle: Single Responsibility (SRP)
 * - Responsabilité UNIQUE: livrer le rapport
 * - Séparée de l'export et de la génération des données
 *
 * SOLID Principle: Open/Closed (OCP)
 * - Ajouter Slack, FTP = créer nouvelle classe, zéro modification
 */
interface ReportDeliveryInterface
{
    /**
     * Livre le rapport au destinataire
     *
     * @param string $content Contenu du fichier
     * @param string $filename Nom du fichier
     * @param string $mimeType Type MIME
     * @param string|null $recipient Email ou identifiant du destinataire (null pour download)
     * @return bool Succès de la livraison
     */
    public function deliver(string $content, string $filename, string $mimeType, ?string $recipient = null): bool;

    /**
     * Retourne le nom de la méthode de livraison
     */
    public function getDeliveryMethod(): string;
}
