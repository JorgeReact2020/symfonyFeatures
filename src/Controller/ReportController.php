<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Report\ReportGeneratorService;
use App\Service\Report\DTO\ReportRequest;
use App\Service\Report\ValueObject\ReportPeriod;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur de test pour le système de reporting
 *
 * Démonstration complète des principes SOLID
 */
#[Route('/report')]
class ReportController extends AbstractController
{
    public function __construct(
        private ReportGeneratorService $reportService
    ) {
    }

    /**
     * Test 1: Formats disponibles (OCP + ISP)
     * Montre que chaque format a des capacités différentes
     */
    #[Route('/test1', name: 'report_test1')]
    public function test1(): Response
    {
        $output = "=== TEST 1: FORMATS DISPONIBLES (OCP + ISP) ===\n\n";

        $formats = $this->reportService->getAvailableFormats();


        $output .= "Formats enregistrés automatiquement via tagged services:\n";
        foreach ($formats as $format) {
            $output .= "\n• {$format['format']} ({$format['extension']})\n";
            $output .= "  MIME: {$format['mimeType']}\n";
            $output .= "  Graphiques: " . ($format['supportsCharts'] ? '✅ OUI' : '❌ NON') . "\n";
            $output .= "  Formatage: " . ($format['supportsFormatting'] ? '✅ OUI' : '❌ NON') . "\n";
        }

        $output .= "\n\n💡 Principe ISP (Interface Segregation):\n";
        $output .= "   - CSV et JSON n'implémentent QUE ReportExporterInterface\n";
        $output .= "   - PDF et Excel implémentent 3 interfaces (+ ChartCapable + FormattingCapable)\n";
        $output .= "   - CSV n'est PAS FORCÉ d'implémenter addChart() et throw exception\n";
        $output .= "   - C'est ça ISP: interfaces ségrégées selon les CAPACITÉS réelles\n";

        return new Response('<pre>' . htmlspecialchars($output) . '</pre>');
    }

    /**
     * Test 2: Export CSV (simple, sans graphiques)
     */
    #[Route('/test2', name: 'report_test2')]
    public function test2(): Response
    {
        $output = "=== TEST 2: EXPORT CSV (Format simple) ===\n\n";

        // Période de 7 jours
        $period = new ReportPeriod(
            new \DateTimeImmutable('-7 days'),
            new \DateTimeImmutable('today')
        );

        $request = new ReportRequest($period, 'csv');

        try {
            $result = $this->reportService->generate($request);

            $output .= "✅ Rapport généré avec succès\n\n";
            $output .= "Fichier: {$result->getFilename()}\n";
            $output .= "Type: {$result->getMimeType()}\n";
            $output .= "Taille: " . strlen($result->getContent()) . " bytes\n";
            $output .= "Généré le: {$result->getGeneratedAt()->format('d/m/Y H:i:s')}\n\n";
            $output .= "--- CONTENU CSV ---\n";
            $output .= $result->getContent();
            $output .= "\n--- FIN ---\n\n";

            $output .= "💡 Principe LSP (Liskov Substitution):\n";
            $output .= "   - CsvExporter est utilisé via ReportExporterInterface\n";
            $output .= "   - Le service ne sait PAS qu'il utilise CSV\n";
            $output .= "   - Tous les exporters sont substituables\n";

        } catch (\Exception $e) {
            $output .= "❌ Erreur: {$e->getMessage()}\n";
        }

        return new Response('<pre>' . htmlspecialchars($output) . '</pre>');
    }

    /**
     * Test 3: Export JSON (pour APIs)
     */
    #[Route('/test3', name: 'report_test3')]
    public function test3(): Response
    {
        $output = "=== TEST 3: EXPORT JSON (Format API) ===\n\n";

        $period = new ReportPeriod(
            new \DateTimeImmutable('-3 days'),
            new \DateTimeImmutable('today')
        );

        $request = new ReportRequest($period, 'json');

        try {
            $result = $this->reportService->generate($request);

            $output .= "✅ Rapport JSON généré\n\n";
            $output .= "Fichier: {$result->getFilename()}\n";
            $output .= "Type: {$result->getMimeType()}\n\n";
            $output .= "--- CONTENU JSON ---\n";
            $output .= $result->getContent();
            $output .= "\n--- FIN ---\n\n";

            $output .= "💡 Utilisation: Parfait pour intégrations et APIs\n";

        } catch (\Exception $e) {
            $output .= "❌ Erreur: {$e->getMessage()}\n";
        }

        return new Response('<pre>' . htmlspecialchars($output) . '</pre>');
    }

    /**
     * Test 4: Export PDF avec graphiques (ISP démo)
     */
    #[Route('/test4', name: 'report_test4')]
    public function test4(): Response
    {
        $output = "=== TEST 4: EXPORT PDF AVEC GRAPHIQUES (ISP) ===\n\n";

        $period = new ReportPeriod(
            new \DateTimeImmutable('-7 days'),
            new \DateTimeImmutable('today')
        );

        $request = new ReportRequest($period, 'pdf');

        try {
            $result = $this->reportService->generate($request);

            $output .= "✅ Rapport PDF généré avec graphiques et styles\n\n";
            $output .= "Fichier: {$result->getFilename()}\n";
            $output .= "Type: {$result->getMimeType()}\n";
            $output .= "Taille: " . strlen($result->getContent()) . " bytes\n\n";

            $output .= "💡 Principe ISP en action:\n";
            $output .= "   - Le service vérifie: if (\$exporter instanceof ChartCapableInterface)\n";
            $output .= "   - Seulement PDF et Excel entrent dans ce IF\n";
            $output .= "   - CSV et JSON ne sont PAS forcés d'implémenter ces méthodes\n";
            $output .= "   - Chaque exporter implémente SEULEMENT ce qu'il PEUT faire\n\n";

            $output .= "--- APERÇU DU PDF (HTML) ---\n";
            $output .= substr($result->getContent(), 0, 1000) . "...\n";
            $output .= "--- FIN ---\n";

        } catch (\Exception $e) {
            $output .= "❌ Erreur: {$e->getMessage()}\n";
        }

        return new Response('<pre>' . htmlspecialchars($output) . '</pre>');
    }

    /**
     * Test 5: Export Excel avec formatage
     */
    #[Route('/test5', name: 'report_test5')]
    public function test5(): Response
    {
        $output = "=== TEST 5: EXPORT EXCEL AVEC FORMATAGE ===\n\n";

        $period = new ReportPeriod(
            new \DateTimeImmutable('-5 days'),
            new \DateTimeImmutable('today')
        );

        $request = new ReportRequest($period, 'excel');

        try {
            $result = $this->reportService->generate($request);

            $output .= "✅ Rapport Excel généré\n\n";
            $output .= "Fichier: {$result->getFilename()}\n";
            $output .= "Type: {$result->getMimeType()}\n\n";
            $output .= "--- CONTENU EXCEL ---\n";
            $output .= $result->getContent();
            $output .= "\n--- FIN ---\n";

        } catch (\Exception $e) {
            $output .= "❌ Erreur: {$e->getMessage()}\n";
        }

        return new Response('<pre>' . htmlspecialchars($output) . '</pre>');
    }

    /**
     * Test 6: Livraison par email (SRP + OCP)
     */
    #[Route('/test6', name: 'report_test6')]
    public function test6(): Response
    {
        $output = "=== TEST 6: LIVRAISON PAR EMAIL (SRP) ===\n\n";

        $period = new ReportPeriod(
            new \DateTimeImmutable('-7 days'),
            new \DateTimeImmutable('today')
        );

        $request = new ReportRequest(
            $period,
            'pdf',
            [],
            'email',
            'manager@example.com'
        );

        try {
            $result = $this->reportService->generate($request);

            $output .= "✅ Rapport généré ET livré\n\n";
            $output .= "Fichier: {$result->getFilename()}\n";
            $output .= "Livré: " . ($result->isDelivered() ? 'OUI' : 'NON') . "\n";
            $output .= "Méthode: {$result->getDeliveryMethod()}\n\n";

            $output .= "💡 Principe SRP (Single Responsibility):\n";
            $output .= "   - ReportDataProvider: récupère les données\n";
            $output .= "   - PdfExporter: génère le PDF\n";
            $output .= "   - EmailDelivery: envoie l'email\n";
            $output .= "   - ReportGeneratorService: orchestre (ne fait PAS le travail)\n";
            $output .= "   - Chaque classe a UNE SEULE responsabilité\n";

        } catch (\Exception $e) {
            $output .= "❌ Erreur: {$e->getMessage()}\n";
        }

        return new Response('<pre>' . htmlspecialchars($output) . '</pre>');
    }

    /**
     * Test 7: Format invalide (gestion d'erreur OCP)
     */
    #[Route('/test7', name: 'report_test7')]
    public function test7(): Response
    {
        $output = "=== TEST 7: FORMAT INVALIDE (Exception) ===\n\n";

        $period = new ReportPeriod(
            new \DateTimeImmutable('-7 days'),
            new \DateTimeImmutable('today')
        );

        // Format qui n'existe pas
        $request = new ReportRequest($period, 'xml');

        try {
            $result = $this->reportService->generate($request);
            $output .= "Pas d'erreur (bizarre!)\n";

        } catch (\Exception $e) {
            $output .= "✅ Exception attrapée correctement:\n";
            $output .= "   Type: " . get_class($e) . "\n";
            $output .= "   Message: {$e->getMessage()}\n\n";

            $output .= "💡 Principe OCP (Open/Closed):\n";
            $output .= "   - Pour ajouter le format XML:\n";
            $output .= "     1. Créer XmlReportExporter implements ReportExporterInterface\n";
            $output .= "     2. Ajouter le tag 'report.exporter' dans services.yaml\n";
            $output .= "     3. C'est TOUT ! Zéro modification dans:\n";
            $output .= "        - ReportGeneratorService\n";
            $output .= "        - ReportController\n";
            $output .= "        - Les autres exporters\n";
            $output .= "   - Le système est FERMÉ aux modifications\n";
            $output .= "   - Mais OUVERT aux extensions\n";
        }

        return new Response('<pre>' . htmlspecialchars($output) . '</pre>');
    }

    /**
     * Test 8: Validation période (ValueObject)
     */
    #[Route('/test8', name: 'report_test8')]
    public function test8(): Response
    {
        $output = "=== TEST 8: VALIDATION PÉRIODE (ValueObject) ===\n\n";

        try {
            // Tentative de créer une période invalide (from > to)
            $period = new ReportPeriod(
                new \DateTimeImmutable('2025-12-31'),
                new \DateTimeImmutable('2025-01-01')
            );

            $output .= "Période créée (ne devrait pas arriver ici !)\n";

        } catch (\InvalidArgumentException $e) {
            $output .= "✅ Validation réussie (période invalide rejetée)\n";
            $output .= "   Message: {$e->getMessage()}\n\n";

            $output .= "💡 Différence ValueObject vs DTO:\n";
            $output .= "   ValueObject (ReportPeriod):\n";
            $output .= "   - Possède des RÈGLES MÉTIER (from < to, max 1 an)\n";
            $output .= "   - IMMUTABLE (pas de setters)\n";
            $output .= "   - Lance exception si invalide\n\n";
            $output .= "   DTO (ReportRequest):\n";
            $output .= "   - AUCUNE règle métier\n";
            $output .= "   - Juste transport de données\n";
            $output .= "   - Peut être mutable\n";
        }

        try {
            // Tentative période trop longue (> 1 an)
            $output .= "\n\nTest période > 1 an:\n";
            $period = new ReportPeriod(
                new \DateTimeImmutable('2023-01-01'),
                new \DateTimeImmutable('2025-12-31')
            );

            $output .= "Période créée (ne devrait pas arriver !)\n";

        } catch (\InvalidArgumentException $e) {
            $output .= "✅ Validation réussie (période > 365 jours rejetée)\n";
            $output .= "   Message: {$e->getMessage()}\n";
        }

        return new Response('<pre>' . htmlspecialchars($output) . '</pre>');
    }

    /**
     * Test 9: DIP démonstration complète
     */
    #[Route('/test9', name: 'report_test9')]
    public function test9(): Response
    {
        $output = "=== TEST 9: DIP (Dependency Inversion Principle) ===\n\n";

        $output .= "💡 Principe DIP dans ReportGeneratorService:\n\n";

        $output .= "AVANT DIP (mauvais):\n";
        $output .= "```php\n";
        $output .= "class ReportGeneratorService {\n";
        $output .= "    private MockSalesDataProvider \$dataProvider;  // ❌ Dépend de concret\n";
        $output .= "    private CsvExporter \$csvExporter;            // ❌ Dépend de concret\n";
        $output .= "    private PdfExporter \$pdfExporter;            // ❌ Dépend de concret\n";
        $output .= "}\n";
        $output .= "```\n\n";

        $output .= "APRÈS DIP (bon):\n";
        $output .= "```php\n";
        $output .= "class ReportGeneratorService {\n";
        $output .= "    private ReportDataProviderInterface \$dataProvider;  // ✅ Interface\n";
        $output .= "    private iterable \$exporters;                       // ✅ Collection par interface\n";
        $output .= "}\n";
        $output .= "```\n\n";

        $output .= "Avantages:\n";
        $output .= "1. MockSalesDataProvider peut être remplacé par DatabaseSalesDataProvider\n";
        $output .= "2. Ajouter un nouvel exporter ne modifie pas le service\n";
        $output .= "3. Les tests peuvent injecter des mocks facilement\n";
        $output .= "4. Le service ne dépend QUE d'abstractions\n\n";

        $output .= "Formats actuellement enregistrés:\n";
        $formats = $this->reportService->getAvailableFormats();
        foreach ($formats as $format) {
            $output .= "  • {$format['format']}\n";
        }

        $output .= "\nMéthodes de livraison enregistrées:\n";
        $methods = $this->reportService->getAvailableDeliveryMethods();
        foreach ($methods as $method) {
            $output .= "  • {$method}\n";
        }

        $output .= "\n✨ Tout cela grâce au tagged_iterator de Symfony !\n";
        $output .= "   Les classes sont enregistrées automatiquement via les tags\n";
        $output .= "   Le service les reçoit via l'interface, pas la classe concrète\n";

        return new Response('<pre>' . htmlspecialchars($output) . '</pre>');
    }

    /**
     * Index - Liste des tests
     */
    #[Route('/', name: 'report_index')]
    public function index(): Response
    {
        $output = "╔══════════════════════════════════════════════════════════════════╗\n";
        $output .= "║     SYSTÈME DE REPORTING - DÉMONSTRATION PRINCIPES SOLID        ║\n";
        $output .= "╚══════════════════════════════════════════════════════════════════╝\n\n";

        $output .= "Tests disponibles:\n\n";
        $output .= "1. /report/test1 - Formats disponibles (OCP + ISP)\n";
        $output .= "2. /report/test2 - Export CSV (format simple, sans graphiques)\n";
        $output .= "3. /report/test3 - Export JSON (pour APIs)\n";
        $output .= "4. /report/test4 - Export PDF avec graphiques (ISP démo) ⭐\n";
        $output .= "5. /report/test5 - Export Excel avec formatage\n";
        $output .= "6. /report/test6 - Livraison par email (SRP)\n";
        $output .= "7. /report/test7 - Format invalide (gestion erreur OCP)\n";
        $output .= "8. /report/test8 - Validation période (ValueObject)\n";
        $output .= "9. /report/test9 - DIP démonstration complète ⭐\n\n";

        $output .= "═══════════════════════════════════════════════════════════════════\n\n";

        $output .= "📚 PRINCIPES SOLID DÉMONTRÉS:\n\n";

        $output .= "✅ SRP (Single Responsibility):\n";
        $output .= "   • ReportDataProvider: récupère les données\n";
        $output .= "   • Exporter: génère le fichier\n";
        $output .= "   • Delivery: livre le fichier\n";
        $output .= "   • Service: orchestre (ne fait pas le travail)\n\n";

        $output .= "✅ OCP (Open/Closed):\n";
        $output .= "   • Ajouter XML exporter = créer classe + tag\n";
        $output .= "   • ZÉRO modification du service ou contrôleur\n";
        $output .= "   • Tagged services pour auto-enregistrement\n\n";

        $output .= "✅ LSP (Liskov Substitution):\n";
        $output .= "   • Tous les exporters substituables via interface\n";
        $output .= "   • Le service ne sait pas quel exporter il utilise\n";
        $output .= "   • Aucun if (\$exporter instanceof PdfExporter)\n\n";

        $output .= "✅ ISP (Interface Segregation): ⭐⭐⭐\n";
        $output .= "   • CSV implémente SEULEMENT ReportExporterInterface\n";
        $output .= "   • PDF implémente 3 interfaces (+ ChartCapable + FormattingCapable)\n";
        $output .= "   • CSV n'est PAS forcé d'implémenter addChart()\n";
        $output .= "   • Pas de throw exception dans les méthodes 'non supportées'\n\n";

        $output .= "✅ DIP (Dependency Inversion):\n";
        $output .= "   • Service dépend d'INTERFACES uniquement\n";
        $output .= "   • Ne connaît pas MockSalesDataProvider vs DatabaseSalesDataProvider\n";
        $output .= "   • Ne connaît pas CsvExporter, PdfExporter, etc.\n\n";

        $output .= "═══════════════════════════════════════════════════════════════════\n\n";
        $output .= "🎯 Points clés pour comprendre ISP:\n\n";
        $output .= "SANS ISP (mauvais):\n";
        $output .= "```php\n";
        $output .= "interface ReportExporterInterface {\n";
        $output .= "    public function export(): string;\n";
        $output .= "    public function addChart(): void;     // ❌ Forcé pour CSV !\n";
        $output .= "    public function setStyles(): void;    // ❌ Forcé pour JSON !\n";
        $output .= "}\n\n";
        $output .= "class CsvExporter implements ReportExporterInterface {\n";
        $output .= "    public function addChart(): void {\n";
        $output .= "        throw new Exception('Non supporté');  // ❌ LSP violation !\n";
        $output .= "    }\n";
        $output .= "}\n";
        $output .= "```\n\n";

        $output .= "AVEC ISP (bon):\n";
        $output .= "```php\n";
        $output .= "interface ReportExporterInterface {        // Base pour TOUS\n";
        $output .= "    public function export(): string;\n";
        $output .= "}\n\n";
        $output .= "interface ChartCapableInterface {          // OPTIONNEL\n";
        $output .= "    public function addChart(): void;\n";
        $output .= "}\n\n";
        $output .= "class CsvExporter implements ReportExporterInterface {\n";
        $output .= "    // ✅ N'implémente QUE ce qu'il PEUT faire\n";
        $output .= "}\n\n";
        $output .= "class PdfExporter implements ReportExporterInterface,\n";
        $output .= "                             ChartCapableInterface {\n";
        $output .= "    // ✅ Implémente les 2 interfaces car il PEUT tout faire\n";
        $output .= "}\n";
        $output .= "```\n\n";

        $output .= "Résultat: Pas d'exception, pas de méthodes vides, code propre ! ✨\n";

        return new Response('<pre>' . htmlspecialchars($output) . '</pre>');
    }
}
