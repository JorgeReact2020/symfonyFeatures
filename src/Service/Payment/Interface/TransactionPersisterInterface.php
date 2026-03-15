<?php

declare(strict_types=1);

namespace App\Service\Payment\Interface;

use App\Service\Payment\DTO\TransactionResult;

/**
 * Single Responsibility Principle: Ne fait QUE persister les transactions en base
 * Interface Segregation Principle: Interface minimale pour la persistance
 *
 * Séparée de TransactionAlertInterface pour respecter ISP :
 * - La persistance BDD n'a pas besoin de connaître l'envoi d'emails
 * - L'envoi d'emails n'a pas besoin de connaître la BDD
 */
interface TransactionPersisterInterface
{
    /**
     * Sauvegarde une transaction réussie en base de données
     */
    public function persist(TransactionResult $result): void;
}
