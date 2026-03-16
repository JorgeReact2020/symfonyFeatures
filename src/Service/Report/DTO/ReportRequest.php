<?php

declare(strict_types=1);

namespace App\Service\Report\DTO;

use App\Service\Report\ValueObject\ReportPeriod;

/**
 * DTO pour transporter les paramètres d'une requête de rapport
 *
 * DTO car:
 * - PAS de règles métier (juste transport de données)
 * - Peut avoir des setters (mutable)
 * - Sert à transporter les données utilisateur vers le service
 */
final class ReportRequest
{
    public function __construct(
        private ReportPeriod $period,
        private string $format,
        private array $filters = [],
        private ?string $deliveryMethod = null,
        private ?string $recipient = null
    ) {
    }

    public function getPeriod(): ReportPeriod
    {
        return $this->period;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getDeliveryMethod(): ?string
    {
        return $this->deliveryMethod;
    }

    public function getRecipient(): ?string
    {
        return $this->recipient;
    }
}
