<?php

declare(strict_types=1);

namespace App\Service\Payment\Interface;

use App\Service\Payment\DTO\PaymentDTO;
use App\Service\Payment\DTO\TransactionResult;
use App\Service\Payment\ValueObject\PaymentConstraints;

interface PaymentMethodInterface
{
    public function charge(PaymentDTO $payment): TransactionResult;

    public function refund(string $transactionId, float $amount, string $currency): TransactionResult;

    public function verify(): bool;

    public function getName(): string;

    /**
     * Retourne les contraintes spécifiques à cette méthode de paiement
     * (montants min/max, devises supportées)
     */
    public function getConstraints(): PaymentConstraints;

    /**
     * Retourne le calculateur de frais pour afficher les coûts à l'utilisateur
     */
    public function getFeeCalculator(): FeeCalculatorInterface;
}
