<?php

declare(strict_types=1);

namespace App\Service\Payment;

use App\Service\Payment\DTO\PaymentDTO;
use App\Service\Payment\DTO\TransactionResult;
use App\Service\Payment\Exception\PaymentException;
use App\Service\Payment\Exception\PaymentMethodNotFoundException;
use App\Service\Payment\Interface\PaymentMethodInterface;
use App\Service\Payment\Interface\PaymentValidatorInterface;
use App\Service\Payment\Interface\TransactionAlertInterface;
use App\Service\Payment\Interface\TransactionPersisterInterface;

/**
 * Service orchestrateur pour gérer tous les paiements
 *
 * DÉMONSTRATION DES 5 PRINCIPES SOLID :
 *
 * 1. SRP: Ce service a UNE responsabilité = coordonner le processus de paiement
 *    - Il ne valide pas lui-même (délègue à PaymentValidatorInterface)
 *    - Il ne calcule pas les frais (délègue aux PaymentMethods qui ont FeeCalculatorInterface)
 *    - Il ne persiste pas (délègue à TransactionPersisterInterface)
 *    - Il ne log pas les erreurs (délègue à TransactionAlertInterface)
 *
 * 2. OCP: Ouvert à l'extension, fermé à la modification
 *    - Ajouter CryptoWalletPayment = ajouter config + tag, zéro modification ici
 *    - Les PaymentMethods sont injectés via tagged iterator
 *
 * 3. LSP: Tous les PaymentMethods sont substituables
 *    - Le service ne connaît que l'interface PaymentMethodInterface
 *    - Fonctionne avec n'importe quelle implémentation
 *
 * 4. ISP: Dépend d'interfaces minimales et ségrégées
 *    - TransactionPersisterInterface (BDD uniquement)
 *    - TransactionAlertInterface (alertes uniquement)
 *    - Séparées car responsabilités différentes
 *
 * 5. DIP: Toutes les dépendances sont des ABSTRACTIONS
 *    - Aucune dépendance directe vers des classes concrètes
 *    - Tout passe par des interfaces
 */
class PaymentService
{
    /** @var PaymentMethodInterface[] */
    private array $paymentMethods = [];

    public function __construct(
        iterable $paymentMethods,
        private readonly PaymentValidatorInterface $validator,
        private readonly TransactionPersisterInterface $persister,
        private readonly TransactionAlertInterface $alert
    ) {
        foreach ($paymentMethods as $paymentMethod) {
            $this->paymentMethods[$paymentMethod->getName()] = $paymentMethod;
        }
    }

    /**
     * Traite un paiement avec le moyen de paiement spécifié dans le DTO
     *
     * Orchestration complète :
     * 1. Trouver le payment method (depuis $payment->methodName)
     * 2. Récupérer les contraintes spécifiques du method
     * 3. Validation des règles métier avec ces contraintes
     * 4. Traitement du paiement
     * 5. Persistance en BDD si succès
     * 6. Alerte admin si échec
     */
    public function processPayment(PaymentDTO $payment): TransactionResult
    {
        try {
            // 1. Trouver le moyen de paiement depuis le DTO
            $method = $this->findPaymentMethod($payment->methodName);

            // 2. Récupérer les contraintes spécifiques au payment method
            // (Stripe: 0.50€ min, PayPal: 1€ min, BankTransfer: 100€ min, etc.)
            $constraints = $method->getConstraints();

            // 3. Valider avec les contraintes spécifiques (délégué au validator)
            $this->validator->validate($payment, $constraints);

            // 4. Traiter le paiement (délégué au PaymentMethod)
            $result = $method->charge($payment);

            // 5. Persister en BDD si succès (délégué au persister)
            if ($result->success) {
                $this->persister->persist($result);
            }

            return $result;

        } catch (PaymentException $e) {
            // 6. Alerter en cas d'échec (délégué à l'alert)
            $this->alert->alertFailure($payment, $payment->methodName, $e->getMessage());

            // Retourner un résultat d'échec
            return TransactionResult::failure(
                paymentMethod: $payment->methodName,
                amount: $payment->amount->getValue(),
                currency: $payment->currency->getCode(),
                customerId: $payment->customerId,
                errorMessage: $e->getMessage(),
                metadata: $payment->metadata
            );
        }
    }

    /**
     * Rembourse une transaction
     */
    public function refund(string $transactionId, string $methodName, float $amount, string $currency): TransactionResult
    {
        $method = $this->findPaymentMethod($methodName);

        try {
            $result = $method->refund($transactionId, $amount, $currency);

            if ($result->success) {
                $this->persister->persist($result);
            }

            return $result;

        } catch (PaymentException $e) {
            echo "❌ Échec remboursement: {$e->getMessage()}\n";
            echo str_repeat('-', 80) . "\n";

            return TransactionResult::failure(
                paymentMethod: $methodName,
                amount: $amount,
                currency: $currency,
                customerId: 'unknown',
                errorMessage: $e->getMessage()
            );
        }
    }

    /**
     * Liste tous les moyens de paiement disponibles avec leurs frais
     */
    public function getAvailablePaymentMethods(): array
    {
        $methods = [];

        foreach ($this->paymentMethods as $method) {
            $methods[] = [
                'name' => $method->getName(),
                'available' => $method->verify(),
                'feesDescription' => $method->getFeeCalculator()->getDescription()
            ];
        }

        return $methods;
    }

    /**
     * Trouve un moyen de paiement par son nom
     *
     * @throws PaymentMethodNotFoundException
     */
    private function findPaymentMethod(string $name): PaymentMethodInterface
    {
        if (!isset($this->paymentMethods[$name])) {
            throw new PaymentMethodNotFoundException(
                $name,
                array_keys($this->paymentMethods)
            );
        }

        return $this->paymentMethods[$name];
    }
}
