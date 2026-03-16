<?php

declare(strict_types=1);

namespace App\Service\Report\Interface;

/**
 * Interface pour les fournisseurs de données de rapport
 *
 * SOLID Principle: Dependency Inversion (DIP)
 * - Le service dépend de cette abstraction, pas d'implémentation concrète
 *
 * SOLID Principle: Single Responsibility (SRP)
 * - Une seule responsabilité: fournir les données
 * - Ne sait pas comment elles seront exportées ou livrées
 */
interface ReportDataProviderInterface
{
    /**
     * Récupère les données de ventes pour une période donnée
     *
     * @param \DateTimeInterface $from Date de début
     * @param \DateTimeInterface $to Date de fin
     * @return array<int, array{date: string, product: string, quantity: int, unitPrice: float, total: float}>
     */
    public function getSalesData(\DateTimeInterface $from, \DateTimeInterface $to): array;

    /**
     * Retourne les métadonnées du rapport (titre, période, générateur)
     *
     * @return array{title: string, period: string, generator: string, generatedAt: string}
     */
    public function getReportMetadata(\DateTimeInterface $from, \DateTimeInterface $to): array;
}
