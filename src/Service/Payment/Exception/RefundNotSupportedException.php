<?php

declare(strict_types=1);

namespace App\Service\Payment\Exception;

/**
 * Exception levée quand un moyen de paiement ne supporte pas les remboursements instantanés
 *
 * Exemple: Bank Transfer nécessite 3-5 jours
 */
class RefundNotSupportedException extends PaymentException
{
    public function __construct(string $paymentMethod)
    {
        parent::__construct("Le moyen de paiement '{$paymentMethod}' ne supporte pas les remboursements instantanés");
    }
}
