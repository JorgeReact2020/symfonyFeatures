<?php

declare(strict_types=1);

namespace App\Service\Report\Interface;

/**
 * Interface pour les exporters supportant les graphiques
 *
 * SOLID Principle: Interface Segregation (ISP)
 * - Séparée de ReportExporterInterface
 * - Seulement PDF et Excel implémentent cette interface
 * - CSV et JSON ne sont PAS forcés d'implémenter addChart()
 *
 * Évite la violation LSP:
 * - Sans ISP: CsvExporter devrait lancer exception sur addChart()
 * - Avec ISP: CsvExporter n'implémente simplement pas cette interface
 */
interface ChartCapableInterface
{
    /**
     * Ajoute un graphique au rapport
     *
     * @param string $type Type de graphique (bar, line, pie)
     * @param array $chartData Données du graphique
     */
    public function addChart(string $type, array $chartData): void;

    /**
     * Vérifie si les graphiques sont supportés
     */
    public function supportsCharts(): bool;
}
