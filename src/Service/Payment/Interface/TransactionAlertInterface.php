<?php

declare(strict_types=1);

namespace App\Service\Payment\Interface;

use App\Service\Payment\DTO\PaymentDTO;

/**
 * Single Responsibility Principle: Ne fait QUE alerter en cas d'échec
 * Interface Segregation Principle: Interface minimale pour les alertes
 *
 * Séparée de TransactionPersisterInterface pour respecter ISP :
 * - Alerter n'a pas besoin de connaître la logique de persistance
 * - Peut avoir plusieurs implémentations (email, SMS, Slack, etc.)
 */
interface TransactionAlertInterface
{
    /**
     * Envoie une alerte en cas de transaction échouée
     */
    public function alertFailure(PaymentDTO $payment, string $paymentMethod, string $reason): void;
}
