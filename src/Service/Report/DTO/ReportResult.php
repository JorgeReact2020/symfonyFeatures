<?php

declare(strict_types=1);

namespace App\Service\Report\DTO;

/**
 * DTO pour transporter le résultat de la génération d'un rapport
 *
 * DTO car:
 * - PAS de règles métier
 * - Juste transport de données du service vers le contrôleur
 */
final class ReportResult
{
    public function __construct(
        private string $filename,
        private string $content,
        private string $mimeType,
        private \DateTimeImmutable $generatedAt,
        private ?string $deliveryMethod = null,
        private bool $delivered = false
    ) {
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getGeneratedAt(): \DateTimeImmutable
    {
        return $this->generatedAt;
    }

    public function getDeliveryMethod(): ?string
    {
        return $this->deliveryMethod;
    }

    public function isDelivered(): bool
    {
        return $this->delivered;
    }

    public function markAsDelivered(string $deliveryMethod): void
    {
        $this->delivered = true;
        $this->deliveryMethod = $deliveryMethod;
    }
}
