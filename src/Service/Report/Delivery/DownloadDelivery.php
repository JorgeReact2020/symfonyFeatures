<?php

declare(strict_types=1);

namespace App\Service\Report\Delivery;

use App\Service\Report\Interface\ReportDeliveryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Livraison par téléchargement direct (HTTP Response)
 *
 * SOLID Principle: Single Responsibility (SRP)
 * - Responsabilité unique: préparer le téléchargement
 * - Ne génère PAS le rapport (c'est le rôle de l'exporter)
 * - Ne récupère PAS les données (c'est le rôle du provider)
 */
class DownloadDelivery implements ReportDeliveryInterface
{
    public function deliver(string $content, string $filename, string $mimeType, ?string $recipient = null): bool
    {
        // Dans une vraie application Symfony, on retournerait une Response
        // Ici, on simule juste le succès

        // En production:
        // return new Response($content, 200, [
        //     'Content-Type' => $mimeType,
        //     'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        // ]);

        // Simulation: on "télécharge" (affiche info)
        echo "\n📥 TÉLÉCHARGEMENT PRÉPARÉ:\n";
        echo "   Fichier: {$filename}\n";
        echo "   Type: {$mimeType}\n";
        echo "   Taille: " . strlen($content) . " bytes\n";
        echo "   Pour: {$recipient}\n";

        return true;
    }

    public function getDeliveryMethod(): string
    {
        return 'download';
    }
}
