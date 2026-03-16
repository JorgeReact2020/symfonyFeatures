<?php

declare(strict_types=1);

namespace App\Service\Report\Exporter;

use App\Service\Report\Interface\ReportExporterInterface;
use App\Service\Report\Interface\ChartCapableInterface;
use App\Service\Report\Interface\FormattingCapableInterface;

/**
 * Exporter au format PDF avec support graphiques et styles
 *
 * SOLID Principle: Interface Segregation (ISP)
 * - Implémente ReportExporterInterface (obligatoire)
 * - Implémente ChartCapableInterface (capacité optionnelle)
 * - Implémente FormattingCapableInterface (capacité optionnelle)
 *
 * Démonstration ISP:
 * - PDF peut ajouter des graphiques → implémente ChartCapable
 * - CSV ne peut pas → n'implémente pas ChartCapable
 * - C'est ISP: interfaces ségrégées selon les CAPACITÉS réelles
 */
class PdfReportExporter implements
    ReportExporterInterface,
    ChartCapableInterface,
    FormattingCapableInterface
{
    private array $charts = [];
    private array $styles = [
        'titleColor' => '#2c3e50',
        'headerColor' => '#34495e',
        'borderColor' => '#bdc3c7',
    ];

    public function export(array $data, array $metadata): string
    {
        // Génération simplifiée d'un "PDF" (HTML pour démo)
        // En production: utiliser TCPDF ou Dompdf

        $html = "<!DOCTYPE html>\n";
        $html .= "<html>\n<head>\n";
        $html .= "<style>\n";
        $html .= "body { font-family: Arial, sans-serif; }\n";
        $html .= "h1 { color: {$this->styles['titleColor']}; }\n";
        $html .= "table { border-collapse: collapse; width: 100%; }\n";
        $html .= "th { background-color: {$this->styles['headerColor']}; color: white; padding: 10px; }\n";
        $html .= "td { border: 1px solid {$this->styles['borderColor']}; padding: 8px; }\n";
        $html .= ".total { font-weight: bold; background-color: #ecf0f1; }\n";
        $html .= "</style>\n";
        $html .= "</head>\n<body>\n";

        // En-tête
        $html .= "<h1>{$metadata['title']}</h1>\n";
        $html .= "<p><strong>Période:</strong> {$metadata['period']}</p>\n";
        $html .= "<p><strong>Généré le:</strong> {$metadata['generatedAt']}</p>\n";

        // Graphiques
        if (!empty($this->charts)) {
            $html .= "<h2>Graphiques</h2>\n";
            foreach ($this->charts as $chart) {
                $html .= "<div class='chart'>\n";
                $html .= "<p><strong>Graphique:</strong> {$chart['type']}</p>\n";
                $html .= "<p><em>Données du graphique: " . count($chart['data']) . " points</em></p>\n";
                $html .= "</div>\n";
            }
        }

        // Tableau de données
        $html .= "<h2>Détail des ventes</h2>\n";
        $html .= "<table>\n";
        $html .= "<tr><th>Date</th><th>Produit</th><th>Quantité</th><th>Prix unitaire</th><th>Total</th></tr>\n";

        $total = 0;
        foreach ($data as $row) {
            $html .= "<tr>";
            $html .= "<td>{$row['date']}</td>";
            $html .= "<td>{$row['product']}</td>";
            $html .= "<td>{$row['quantity']}</td>";
            $html .= "<td>" . number_format($row['unitPrice'], 2, ',', ' ') . " €</td>";
            $html .= "<td>" . number_format($row['total'], 2, ',', ' ') . " €</td>";
            $html .= "</tr>\n";
            $total += $row['total'];
        }

        $html .= "<tr class='total'>";
        $html .= "<td colspan='4'>TOTAL</td>";
        $html .= "<td>" . number_format($total, 2, ',', ' ') . " €</td>";
        $html .= "</tr>\n";

        $html .= "</table>\n";
        $html .= "</body>\n</html>";

        return $html;
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
        return 'pdf';
    }

    public function getMimeType(): string
    {
        return 'application/pdf';
    }

    public function getFileExtension(): string
    {
        return 'pdf';
    }
}
