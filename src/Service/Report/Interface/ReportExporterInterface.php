<?php

declare(strict_types=1);

namespace App\Service\Report\Interface;

/**
 * Interface de base pour tous les exporters de rapport
 *
 * SOLID Principle: Open/Closed (OCP)
 * - Extensible: ajouter XML, HTML = créer nouvelle classe
 * - Fermé: pas besoin de modifier le service orchestrateur
 *
 * SOLID Principle: Liskov Substitution (LSP)
 * - Tous les exporters sont substituables
 * - Le service ne sait pas quel exporter il utilise
 */
interface ReportExporterInterface
{
    /**
     * Exporte les données dans le format spécifique
     *
     * @param array $data Données à exporter
     * @param array $metadata Métadonnées du rapport
     * @return string Contenu du fichier généré
     */
    public function export(array $data, array $metadata): string;

    /**
     * Retourne le nom du format (pdf, excel, csv, json)
     */
    public function getFormat(): string;

    /**
     * Retourne le MIME type pour les headers HTTP
     */
    public function getMimeType(): string;

    /**
     * Retourne l'extension de fichier
     */
    public function getFileExtension(): string;
}