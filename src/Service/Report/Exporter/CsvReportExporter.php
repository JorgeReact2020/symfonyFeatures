<?php

declare(strict_types=1);

namespace App\Service\Report\Exporter;

use App\Service\Report\Interface\ReportExporterInterface;use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
/**
 * Exporter au format CSV
 *
 * SOLID Principle: Interface Segregation (ISP)
 * - N'implémente QUE ReportExporterInterface
 * - N'implémente PAS ChartCapableInterface (CSV ne peut pas faire de graphiques)
 * - N'implémente PAS FormattingCapableInterface (CSV n'a pas de styles)
 *
 * Pourquoi c'est important:
 * - Si ChartCapable était dans ReportExporterInterface, on serait forcé d'implémenter addChart()
 * - On devrait throw exception → violation LSP (pas substitutable)
 * - Avec ISP, CSV est une implémentation valide et simple
 */
class CsvReportExporter implements ReportExporterInterface
{
    public function export(array $data, array $metadata): string
    {
        $csv = [];

        // En-tête avec métadonnées
        $csv[] = [$metadata['title']];
        $csv[] = [$metadata['period']];
        $csv[] = ['Généré le: ' . $metadata['generatedAt']];
        $csv[] = []; // Ligne vide

        // En-têtes des colonnes
        $csv[] = ['Date', 'Produit', 'Quantité', 'Prix unitaire', 'Total'];

        // Données
        foreach ($data as $row) {
            $csv[] = [
                $row['date'],
                $row['product'],
                $row['quantity'],
                number_format($row['unitPrice'], 2, ',', ' ') . ' €',
                number_format($row['total'], 2, ',', ' ') . ' €',
            ];
        }

        // Ligne vide + total
        $total = array_sum(array_column($data, 'total'));
        $csv[] = [];
        $csv[] = ['', '', '', 'TOTAL:', number_format($total, 2, ',', ' ') . ' €'];

        // Conversion en CSV
        $output = fopen('php://temp', 'r+');
        foreach ($csv as $row) {
            fputcsv($output, $row, ';');
        }
        rewind($output);
        $result = stream_get_contents($output);
        fclose($output);

        return $result;
    }

    public function getFormat(): string
    {
        return 'csv';
    }

    public function getMimeType(): string
    {
        return 'text/csv';
    }

    public function getFileExtension(): string
    {
        return 'csv';
    }
}
