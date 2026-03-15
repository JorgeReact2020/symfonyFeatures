<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Payment\DTO\PaymentDTO;
use App\Service\Payment\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur pour tester le système de paiement SOLID
 */
class PaymentController extends AbstractController
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {}

    #[Route('/payment/test', name: 'payment_test')]
    public function test(): Response
    {
        ob_start();

        echo "═══════════════════════════════════════════════════════════════════════════════\n";
        echo "       SYSTÈME DE PAIEMENT - EXERCICE SOLID - Tests Automatiques\n";
        echo "═══════════════════════════════════════════════════════════════════════════════\n\n";

        // Test 1: Paiement Stripe 100€
        $this->test1_stripePayment();

        // Test 2: Paiement PayPal 50€
        $this->test2_paypalPayment();

        // Test 3: Paiement invalide (montant trop bas)
        $this->test3_invalidPayment();

        // Test 4: Remboursement Stripe
        $this->test4_stripeRefund();

        // Test 5: Liste des moyens de paiement
        $this->test5_listPaymentMethods();

        echo "\n═══════════════════════════════════════════════════════════════════════════════\n";
        echo "                              TESTS TERMINÉS\n";
        echo "═══════════════════════════════════════════════════════════════════════════════\n";

        $output = ob_get_clean();

        return new Response('<pre>' . htmlspecialchars($output) . '</pre>');
    }

    private function test1_stripePayment(): void
    {
        echo "🧪 TEST 1: Paiement de 100€ avec Stripe\n";
        echo str_repeat('═', 80) . "\n";

        $payment = PaymentDTO::create(
            amount: 100.0,
            currency: 'EUR',
            customerId: 'customer_123',
            metadata: ['order_id' => 'ORD-001']
        );

        $result = $this->paymentService->processPayment($payment, 'stripe');

        if ($result->success) {
            echo "✅ Succès: Transaction {$result->transactionId}\n";
            echo "   Montant: {$result->amount}€ + Frais: {$result->fees}€ = Total: {$result->getTotalAmount()}€\n\n";
        } else {
            echo "❌ Échec: {$result->errorMessage}\n\n";
        }
    }

    private function test2_paypalPayment(): void
    {
        echo "🧪 TEST 2: Paiement de 50€ avec PayPal\n";
        echo str_repeat('═', 80) . "\n";

        $payment = PaymentDTO::create(
            amount: 50.0,
            currency: 'EUR',
            customerId: 'customer_456',
            metadata: ['order_id' => 'ORD-002']
        );

        $result = $this->paymentService->processPayment($payment, 'paypal');

        if ($result->success) {
            echo "✅ Succès: Transaction {$result->transactionId}\n";
            echo "   Montant: {$result->amount}€ + Frais: {$result->fees}€ = Total: {$result->getTotalAmount()}€\n\n";
        } else {
            echo "❌ Échec: {$result->errorMessage}\n\n";
        }
    }

    private function test3_invalidPayment(): void
    {
        echo "🧪 TEST 3: Paiement INVALIDE - 2€ (en dessous du minimum de 5€)\n";
        echo str_repeat('═', 80) . "\n";

        $payment = PaymentDTO::create(
            amount: 2.0,
            currency: 'EUR',
            customerId: 'customer_789',
            metadata: ['order_id' => 'ORD-003']
        );

        $result = $this->paymentService->processPayment($payment, 'stripe');

        if ($result->success) {
            echo "❌ ERREUR: Le paiement aurait dû échouer!\n\n";
        } else {
            echo "✅ Validation correcte: {$result->errorMessage}\n\n";
        }
    }

    private function test4_stripeRefund(): void
    {
        echo "🧪 TEST 4: Remboursement Stripe de 25€\n";
        echo str_repeat('═', 80) . "\n";

        // D'abord créer une transaction
        $payment = PaymentDTO::create(
            amount: 25.0,
            currency: 'EUR',
            customerId: 'customer_refund',
            metadata: ['order_id' => 'ORD-004']
        );

        $result = $this->paymentService->processPayment($payment, 'stripe');

        if ($result->success) {
            echo "✅ Paiement initial: {$result->transactionId}\n";
            echo str_repeat('-', 80) . "\n";

            // Puis le rembourser
            $refundResult = $this->paymentService->refund(
                transactionId: $result->transactionId,
                methodName: 'stripe',
                amount: 25.0,
                currency: 'EUR'
            );

            if ($refundResult->success) {
                echo "✅ Remboursement réussi: {$refundResult->transactionId}\n\n";
            } else {
                echo "❌ Échec remboursement: {$refundResult->errorMessage}\n\n";
            }
        }
    }

    private function test5_listPaymentMethods(): void
    {
        echo "🧪 TEST 5: Liste des moyens de paiement disponibles\n";
        echo str_repeat('═', 80) . "\n";

        $methods = $this->paymentService->getAvailablePaymentMethods();

        echo "📋 Moyens de paiement configurés :\n\n";

        foreach ($methods as $method) {
            $status = $method['available'] ? '✅ Disponible' : '❌ Indisponible';
            echo "   • {$method['name']}: {$status}\n";
        }

        echo "\n";
        echo "💡 DÉMONSTRATION OCP (Open/Closed Principle):\n";
        echo "   Pour ajouter 'CryptoWallet', il suffirait de:\n";
        echo "   1. Créer CryptoWalletPayment implements PaymentMethodInterface\n";
        echo "   2. Créer CryptoWalletFeeCalculator implements FeeCalculatorInterface\n";
        echo "   3. Ajouter la config dans services.yaml avec le tag\n";
        echo "   => AUCUNE modification du code existant!\n\n";
    }
}
