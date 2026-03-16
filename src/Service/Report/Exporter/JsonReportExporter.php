<?php

declare(strict_types=1);

namespace App\Service\Report\Exporter;

use App\Service\Report\Interface\ReportExporterInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Exporter au format JSON
 *
 * SOLID Principle: Interface Segregation (ISP)
 * - N'implémente QUE ReportExporterInterface
 * - JSON n'a pas de graphiques ni de formatage visuel
 * - Parfait pour les APIs et les intégrations
 */
class JsonReportExporter implements ReportExporterInterface
{
    public function export(array $data, array $metadata): string
    {
        $total = array_sum(array_column($data, 'total'));

        $report = [
            'metadata' => $metadata,
            'summary' => [
                'totalSales' => count($data),
                'totalAmount' => round($total, 2),
            ],
            'data' => $data,
        ];

        return json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function getFormat(): string
    {
        return 'json';
    }

    public function getMimeType(): string
    {
        return 'application/json';
    }

    public function getFileExtension(): string
    {
        return 'json';
    }
}
