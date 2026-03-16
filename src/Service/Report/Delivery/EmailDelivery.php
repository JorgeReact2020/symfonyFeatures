<?php

declare(strict_types=1);

namespace App\Service\Report\Delivery;

use App\Service\Report\Interface\ReportDeliveryInterface;
use App\Service\Report\Exception\DeliveryFailedException;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Livraison par email avec pièce jointe
 * Dans un vrai projet, utiliser Symfony Mailer
 */
class EmailDelivery implements ReportDeliveryInterface
{
    public function deliver(string $content, string $filename, string $mimeType, ?string $recipient = null): bool
    {
        if ($recipient === null || !filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            throw DeliveryFailedException::forMethod(
                'email',
                $recipient === null ? 'Aucun destinataire fourni' : "Email invalide: {$recipient}"
            );
        }

        // Simulation d'envoi d'email (dans un vrai projet: Symfony Mailer)
        echo "\n📧 EMAIL ENVOYÉ:\n";
        echo "   À: {$recipient}\n";
        echo "   Sujet: Votre rapport - {$filename}\n";
        echo "   Pièce jointe: {$filename}\n";
        echo "   Taille: " . strlen($content) . " bytes\n";
        echo "   Type MIME: {$mimeType}\n\n";

        // Production:
        // $email = (new Email())
        //     ->to($recipient)
        //     ->subject("Votre rapport - {$filename}")
        //     ->text('Veuillez trouver votre rapport en pièce jointe.')
        //     ->attach($content, $filename, $mimeType);
        // $this->mailer->send($email);

        return true;
    }

    public function getDeliveryMethod(): string
    {
        return 'email';
    }
}
