<?php

declare(strict_types=1);

namespace App\Service\Report\DataProvider;

use App\Service\Report\Interface\ReportDataProviderInterface;

/**
 * Fournisseur de données de test pour les rapports
 * Génère des données de ventes fictives
 */
class MockSalesDataProvider implements ReportDataProviderInterface
{
    private const PRODUCTS = [
        'Ordinateur Portable' => ['min' => 800, 'max' => 1500],
        'Souris' => ['min' => 15, 'max' => 50],
        'Clavier' => ['min' => 30, 'max' => 100],
        'Écran' => ['min' => 200, 'max' => 500],
        'Webcam' => ['min' => 50, 'max' => 150],
    ];

    public function getSalesData(\DateTimeInterface $from, \DateTimeInterface $to): array
    {
        $salesData = [];
        $currentDate = \DateTimeImmutable::createFromInterface($from);
        $endDate = \DateTimeImmutable::createFromInterface($to);

        while ($currentDate <= $endDate) {
            // Entre 2 et 5 ventes par jour
            $numberOfSales = random_int(2, 5);

            for ($i = 0; $i < $numberOfSales; $i++) {
                // Produit aléatoire avec prix dans la fourchette
                $productName = array_rand(self::PRODUCTS);
                $priceRange = self::PRODUCTS[$productName];
                $unitPrice = (float) random_int($priceRange['min'], $priceRange['max']);
                $quantity = random_int(1, 3);
                $total = $quantity * $unitPrice;

                // Convertir en array simple pour respecter l'interface
                $salesData[] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'product' => $productName,
                    'quantity' => $quantity,
                    'unitPrice' => $unitPrice,
                    'total' => $total,
                ];
            }

            $currentDate = $currentDate->modify('+1 day');
        }

        return $salesData;
    }

    public function getReportMetadata(\DateTimeInterface $from, \DateTimeInterface $to): array
    {
        return [
            'title' => 'Rapport de Ventes',
            'period' => sprintf(
                'Du %s au %s',
                $from->format('d/m/Y'),
                $to->format('d/m/Y')
            ),
            'dataSource' => 'Mock (données de test)',
            'generatedAt' => (new \DateTimeImmutable())->format('d/m/Y H:i:s'),
            'generator' => 'Système de Reporting SOLID',
        ];
    }
}
