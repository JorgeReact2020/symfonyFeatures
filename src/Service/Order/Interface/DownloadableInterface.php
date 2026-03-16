<?php

declare(strict_types=1);

namespace App\Service\Order\Interface;

/**
 * Interface pour les produits TÉLÉCHARGEABLES
 *
 * SOLID Principle: Interface Segregation (ISP)
 * - Capacité optionnelle pour produits numériques
 * - PhysicalProduct n'a PAS à l'implémenter
 * - ServiceProduct non plus (pas de fichier à télécharger)
 */
interface DownloadableInterface
{
    public function getDownloadUrl(): string;

    public function getFileSize(): int; // en bytes

    public function getFileFormat(): string; // pdf, mp3, mp4, zip, etc.

    public function getDownloadLimit(): ?int; // null = illimité
}
