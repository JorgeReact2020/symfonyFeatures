<?php

declare(strict_types=1);

namespace App\Service\Payment\Logger;

use App\Service\Payment\DTO\TransactionResult;
use App\Service\Payment\Interface\TransactionPersisterInterface;
use Psr\Log\LoggerInterface;

/**
 * Single Responsibility Principle: Persiste UNIQUEMENT les transactions réussies en base
 * Interface Segregation Principle: Interface minimale, séparée de TransactionAlertInterface
 * Dependency Inversion Principle: Dépend de LoggerInterface (abstraction PSR-3)
 *
 * En production, cette classe utiliserait Doctrine pour sauvegarder en BDD
 */
class TransactionPersister implements TransactionPersisterInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    public function persist(TransactionResult $result): void
    {
        // Simuler sauvegarde en base de données
        // En prod: $this->entityManager->persist(new Transaction($result));

        $this->logger->info('✅ Transaction persistée en base de données', [
            'transaction_id' => $result->transactionId,
            'payment_method' => $result->paymentMethod,
            'amount' => $result->amount,
            'fees' => $result->fees,
            'currency' => $result->currency,
            'customer_id' => $result->customerId,
            'processed_at' => $result->processedAt?->format('Y-m-d H:i:s')
        ]);

        echo "💾 Transaction {$result->transactionId} sauvegardée en BDD\n";
        echo str_repeat('-', 80) . "\n";
    }
}
