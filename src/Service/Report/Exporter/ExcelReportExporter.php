<?php

declare(strict_types=1);

namespace App\Service\Report\Exporter;

use App\Service\Report\Interface\ReportExporterInterface;
use App\Service\Report\Interface\ChartCapableInterface;
use App\Service\Report\Interface\FormattingCapableInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Exporter au format Excel avec support graphiques et styles
 *
 * SOLID Principle: Interface Segregation (ISP)
 * - Implémente les 3 interfaces comme PDF
 * - Excel peut faire charts + formatting + export de base
 *
 * SOLID Principle: Liskov Substitution (LSP)
 * - Substitutable à ReportExporterInterface comme tous les autres
 * - Le service ne sait pas si c'est Excel, PDF, CSV ou JSON
 */
class ExcelReportExporter implements
    ReportExporterInterface,
    ChartCapableInterface,
    FormattingCapableInterface
{
    private array $charts = [];
    private array $styles = [
        'headerBold' => true,
        'numberFormat' => '#,##0.00 €',
    ];

    public function export(array $data, array $metadata): string
    {
        // Génération simplifiée d'Excel (CSV amélioré pour démo)
        // En production: utiliser PhpSpreadsheet

        $excel = "=== RAPPORT EXCEL ===\n\n";

        // Métadonnées
        $excel .= "Titre: {$metadata['title']}\n";
        $excel .= "Période: {$metadata['period']}\n";
        $excel .= "Généré le: {$metadata['generatedAt']}\n\n";

        // Styles appliqués
        $excel .= "Styles: " . json_encode($this->styles) . "\n\n";

        // Graphiques
        if (!empty($this->charts)) {
            $excel .= "=== GRAPHIQUES ===\n";
            foreach ($this->charts as $chart) {
                $excel .= "- Type: {$chart['type']}, Points de données: " . count($chart['data']) . "\n";
            }
            $excel .= "\n";
        }

        // Données tabulaires
        $excel .= "=== DONNÉES ===\n";
        $excel .= str_pad('Date', 12) . str_pad('Produit', 25) . str_pad('Qté', 8) . str_pad('P.U.', 12) . str_pad('Total', 12) . "\n";
        $excel .= str_repeat('-', 70) . "\n";

        $total = 0;
        foreach ($data as $row) {
            $excel .= str_pad($row['date'], 12);
            $excel .= str_pad($row['product'], 25);
            $excel .= str_pad((string)$row['quantity'], 8);
            $excel .= str_pad(number_format($row['unitPrice'], 2) . ' €', 12);
            $excel .= str_pad(number_format($row['total'], 2) . ' €', 12);
            $excel .= "\n";
            $total += $row['total'];
        }

        $excel .= str_repeat('-', 70) . "\n";
        $excel .= str_pad('TOTAL:', 57) . str_pad(number_format($total, 2) . ' €', 12) . "\n";

        $excel .= "\n=== FORMULES EXCEL ===\n";
        $excel .= "Formule total: =SUM(E2:E" . (count($data) + 1) . ")\n";

        return $excel;
    }

    /**
     * Implémentation de ChartCapableInterface
     */
    public function addChart(string $type, array $chartData): void
    {
        $this->charts[] = [
            'type' => $type,
            'data' => $chartData,
        ];
    }

    public function supportsCharts(): bool
    {
        return true;
    }

    /**
     * Implémentation de FormattingCapableInterface
     */
    public function setStyles(array $styles): void
    {
        $this->styles = array_merge($this->styles, $styles);
    }

    public function supportsFormatting(): bool
    {
        return true;
    }

    /**
     * Implémentation de ReportExporterInterface
     */
    public function getFormat(): string
    {
        return 'excel';
    }

    public function getMimeType(): string
    {
        return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }

    public function getFileExtension(): string
    {
        return 'xlsx';
    }
}
