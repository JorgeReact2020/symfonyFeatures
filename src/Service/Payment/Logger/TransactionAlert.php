<?php

declare(strict_types=1);

namespace App\Service\Payment\Logger;

use App\Service\Payment\DTO\PaymentDTO;
use App\Service\Payment\Interface\TransactionAlertInterface;
use Psr\Log\LoggerInterface;

/**
 * Single Responsibility Principle: Alerte UNIQUEMENT en cas d'échec de transaction
 * Interface Segregation Principle: Interface minimale, séparée de TransactionPersisterInterface
 * Dependency Inversion Principle: Dépend de LoggerInterface (abstraction PSR-3)
 *
 * En production, cette classe enverrait également un email à l'admin
 */
class TransactionAlert implements TransactionAlertInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    public function alertFailure(PaymentDTO $payment, string $paymentMethod, string $reason): void
    {
        // Logger l'échec (fichier de log)
        $this->logger->error('❌ Échec de transaction', [
            'payment_method' => $paymentMethod,
            'amount' => $payment->amount->getValue(),
            'currency' => $payment->currency->getCode(),
            'customer_id' => $payment->customerId,
            'reason' => $reason
        ]);

        // Simuler envoi d'email admin
        echo "📧 ALERTE ADMIN: Échec paiement {$payment->amount->getValue()}{$payment->currency->getSymbol()}\n";
        echo "   Méthode: {$paymentMethod}\n";
        echo "   Client: {$payment->customerId}\n";
        echo "   Raison: {$reason}\n";
        echo str_repeat('-', 80) . "\n";
    }
}
