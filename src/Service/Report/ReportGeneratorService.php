<?php

declare(strict_types=1);

namespace App\Service\Report;

use App\Service\Report\DTO\ReportRequest;
use App\Service\Report\DTO\ReportResult;
use App\Service\Report\Interface\ReportDataProviderInterface;
use App\Service\Report\Interface\ReportExporterInterface;
use App\Service\Report\Interface\ReportDeliveryInterface;
use App\Service\Report\Interface\ChartCapableInterface;
use App\Service\Report\Interface\FormattingCapableInterface;
use App\Service\Report\Exception\UnsupportedFormatException;
use App\Service\Report\Exception\UnsupportedDeliveryMethodException;

/**
 * Service orchestrateur pour la génération de rapports
 *
 * SOLID Principle: Dependency Inversion (DIP) ⭐
 * - Dépend d'abstractions (interfaces), PAS de classes concrètes
 * - Ne connaît pas CsvExporter, PdfExporter, etc.
 * - Ne connaît pas MockSalesDataProvider vs DatabaseSalesDataProvider
 *
 * SOLID Principle: Open/Closed (OCP) ⭐
 * - FERMÉ: ajouter XML exporter ne nécessite ZÉRO modification ici
 * - OUVERT: nouveaux formats via tagged services
 *
 * SOLID Principle: Liskov Substitution (LSP) ⭐
 * - Tous les exporters sont substituables
 * - Le code ne fait AUCUN if ($exporter instanceof PdfExporter)
 *
 * SOLID Principle: Interface Segregation (ISP) ⭐
 * - Teste supportsCharts() et supportsFormatting()
 * - N'assume pas que TOUS les exporters ont ces capacités
 */
class ReportGeneratorService
{
    /** @var array<string, ReportExporterInterface> */
    private array $exporters = [];

    /** @var array<string, ReportDeliveryInterface> */
    private array $deliveryMethods = [];

    /**
     * @param iterable<ReportExporterInterface> $exporters Tagged services
     * @param iterable<ReportDeliveryInterface> $deliveryServices Tagged services
     */
    public function __construct(
        private ReportDataProviderInterface $dataProvider,
        iterable $exporters,
        iterable $deliveryServices
    ) {
        // Indexation par format pour accès rapide (pas de if/switch!)
        foreach ($exporters as $exporter) {
            $this->exporters[$exporter->getFormat()] = $exporter;
        }

        // Indexation par méthode de livraison
        foreach ($deliveryServices as $delivery) {
            $this->deliveryMethods[$delivery->getDeliveryMethod()] = $delivery;
        }
    }

    /**
     * Génère un rapport selon la requête
     *
     * @throws UnsupportedFormatException
     * @throws UnsupportedDeliveryMethodException
     */
    public function generate(ReportRequest $request): ReportResult
    {
        // 1. Vérifier que le format existe (OCP: pas de if/switch!)
        $format = $request->getFormat();

        if (!isset($this->exporters[$format])) {
            throw UnsupportedFormatException::format($format, array_keys($this->exporters));
        }

        $exporter = $this->exporters[$format];

        // 2. Récupérer les données (DIP: ne sait pas d'où viennent les données)
        $period = $request->getPeriod();
        $data = $this->dataProvider->getSalesData($period->getFrom(), $period->getTo());
        $metadata = $this->dataProvider->getReportMetadata($period->getFrom(), $period->getTo());

        // 3. Appliquer les capacités optionnelles (ISP: vérifier avant d'utiliser!)
        if ($exporter instanceof ChartCapableInterface && $exporter->supportsCharts()) {
            // Ajouter un graphique si l'exporter le supporte
            $chartData = $this->prepareChartData($data);
            $exporter->addChart('bar', $chartData);
        }

        if ($exporter instanceof FormattingCapableInterface && $exporter->supportsFormatting()) {
            // Appliquer des styles si l'exporter le supporte
            $exporter->setStyles([
                'titleColor' => '#3498db',
                'headerColor' => '#2ecc71',
            ]);
        }

        // 4. Générer le rapport (LSP: tous les exporters sont substituables)
        $content = $exporter->export($data, $metadata);

        // 5. Créer le résultat
        $filename = sprintf(
            'rapport_%s_%s.%s',
            strtolower(str_replace(' ', '_', $metadata['title'])),
            date('Ymd'),
            $exporter->getFileExtension()
        );

        $result = new ReportResult(
            $filename,
            $content,
            $exporter->getMimeType(),
            new \DateTimeImmutable()
        );

        // 6. Livraison optionnelle
        $deliveryMethod = $request->getDeliveryMethod();
        if ($deliveryMethod !== null) {
            if (!isset($this->deliveryMethods[$deliveryMethod])) {
                throw UnsupportedDeliveryMethodException::method(
                    $deliveryMethod,
                    array_keys($this->deliveryMethods)
                );
            }

            $delivery = $this->deliveryMethods[$deliveryMethod];
            $recipient = $request->getRecipient() ?? 'user@example.com';

            $success = $delivery->deliver($content, $filename, $exporter->getMimeType(), $recipient);

            if ($success) {
                $result->markAsDelivered($deliveryMethod);
            }
        }

        return $result;
    }

    /**
     * Liste les formats disponibles
     */
    public function getAvailableFormats(): array
    {
        $formats = [];
        foreach ($this->exporters as $format => $exporter) {
            $formats[] = [
                'format' => $format,
                'mimeType' => $exporter->getMimeType(),
                'extension' => $exporter->getFileExtension(),
                'supportsCharts' => $exporter instanceof ChartCapableInterface && $exporter->supportsCharts(),
                'supportsFormatting' => $exporter instanceof FormattingCapableInterface && $exporter->supportsFormatting(),
            ];
        }
        return $formats;
    }

    /**
     * Liste les méthodes de livraison disponibles
     */
    public function getAvailableDeliveryMethods(): array
    {
        return array_keys($this->deliveryMethods);
    }

    /**
     * Prépare les données pour un graphique
     */
    private function prepareChartData(array $data): array
    {
        // Grouper les ventes par produit
        $chartData = [];
        foreach ($data as $row) {
            $product = $row['product'];
            if (!isset($chartData[$product])) {
                $chartData[$product] = 0;
            }
            $chartData[$product] += $row['total'];
        }
        return $chartData;
    }
}
