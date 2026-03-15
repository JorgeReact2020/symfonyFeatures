<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Notification\DTO\NotificationMessage;
use App\Service\Notification\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller de test pour le système de notification SOLID
 */
#[Route('/notification')]
class NotificationController extends AbstractController
{
    public function __construct(
        private readonly NotificationService $notificationService
    ) {}

    /**
     * Page d'accueil avec les liens vers tous les tests
     */
    #[Route('/', name: 'notification_home')]
    public function index(): Response
    {
        $channels = $this->notificationService->getAvailableChannels();

        return $this->render('notification/index.html.twig', [
            'channels' => $channels
        ]);
    }

    /**
     * Test 1 : Envoi simple sur un canal
     */
    #[Route('/test/simple', name: 'notification_test_simple')]
    public function testSimple(): Response
    {
        $message = new NotificationMessage(
            type: 'alert',
            title: 'Maintenance programmée',
            content: 'Le système sera indisponible demain de 2h à 4h',
            recipient: 'user@example.com'
        );

        ob_start();
        $result = $this->notificationService->send($message, 'email');
        $output = ob_get_clean();

        return new Response(
            "<h1>Test 1 : Envoi Simple</h1>" .
            "<pre>Résultat: " . ($result ? 'Succès ✅' : 'Échec ❌') . "\n\n" .
            "Output:\n" . htmlspecialchars($output) . "</pre>" .
            "<a href='/notification'>Retour</a>"
        );
    }

    /**
     * Test 2 : Envoi multi-canal
     */
    #[Route('/test/multi-channel', name: 'notification_test_multi')]
    public function testMultiChannel(): Response
    {
        $message = new NotificationMessage(
            type: 'promotion',
            title: '50% de réduction',
            content: 'Profitez de notre promotion exclusive jusqu\'à dimanche !',
            recipient: '+33612345678'
        );

        ob_start();
        $results = $this->notificationService->sendToAll($message);
        $output = ob_get_clean();

        $resultsHtml = '';
        foreach ($results as $channel => $result) {
            $resultsHtml .= sprintf(
                "%s: %s\n",
                $channel,
                $result ? 'Succès ✅' : 'Échec ❌'
            );
        }

        return new Response(
            "<h1>Test 2 : Multi-Canal</h1>" .
            "<h2>Résultats par canal:</h2>" .
            "<pre>" . htmlspecialchars($resultsHtml) . "</pre>" .
            "<h2>Output:</h2>" .
            "<pre>" . htmlspecialchars($output) . "</pre>" .
            "<a href='/notification'>Retour</a>"
        );
    }

    /**
     * Test 3 : Respect des préférences utilisateur
     * user@example.com a désactivé les SMS
     */
    #[Route('/test/preferences', name: 'notification_test_preferences')]
    public function testPreferences(): Response
    {
        $message = new NotificationMessage(
            type: 'info',
            title: 'Nouvelle fonctionnalité',
            content: 'Découvrez notre nouvelle fonctionnalité de notification',
            recipient: 'user@example.com'
        );

        ob_start();

        echo "=== Test Email (autorisé) ===\n";
        $emailResult = $this->notificationService->send($message, 'email');

        echo "\n=== Test SMS (bloqué par préférences) ===\n";
        $smsResult = $this->notificationService->send($message, 'sms');

        echo "\n=== Test Slack (autorisé) ===\n";
        $slackResult = $this->notificationService->send($message, 'slack');

        $output = ob_get_clean();

        return new Response(
            "<h1>Test 3 : Préférences Utilisateur</h1>" .
            "<p>L'utilisateur user@example.com a désactivé les SMS</p>" .
            "<h2>Résultats:</h2>" .
            "<pre>" .
            "Email: " . ($emailResult ? 'Envoyé ✅' : 'Bloqué ❌') . "\n" .
            "SMS: " . ($smsResult ? 'Envoyé ✅' : 'Bloqué ❌') . " (devrait être bloqué)\n" .
            "Slack: " . ($slackResult ? 'Envoyé ✅' : 'Bloqué ❌') . "\n" .
            "</pre>" .
            "<h2>Output détaillé:</h2>" .
            "<pre>" . htmlspecialchars($output) . "</pre>" .
            "<a href='/notification'>Retour</a>"
        );
    }

    /**
     * Test 4 : Démonstration Open/Closed Principle
     * Liste tous les canaux disponibles
     */
    #[Route('/test/open-closed', name: 'notification_test_open_closed')]
    public function testOpenClosed(): Response
    {
        $channels = $this->notificationService->getAvailableChannels();

        $html = "<h1>Test 4 : Open/Closed Principle</h1>";
        $html .= "<h2>Canaux disponibles:</h2>";
        $html .= "<ul>";

        foreach ($channels as $channel) {
            $html .= "<li>✅ {$channel}</li>";
        }

        $html .= "</ul>";
        $html .= "<h2>Pour ajouter WhatsApp:</h2>";
        $html .= "<ol>";
        $html .= "<li>Créer <code>WhatsAppChannel.php</code> qui implémente <code>NotificationChannelInterface</code></li>";
        $html .= "<li>Créer <code>WhatsAppFormatter.php</code> qui implémente <code>MessageFormatterInterface</code></li>";
        $html .= "<li>Ajouter la config dans <code>services.yaml</code> avec le tag</li>";
        $html .= "<li><strong>AUCUNE modification de NotificationService requis !</strong> ✅</li>";
        $html .= "</ol>";
        $html .= "<p><em>Principe Open/Closed : Ouvert à l'extension, fermé à la modification</em></p>";
        $html .= "<a href='/notification'>Retour</a>";

        return new Response($html);
    }
}
