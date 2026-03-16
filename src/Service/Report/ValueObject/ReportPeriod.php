<?php

declare(strict_types=1);

namespace App\Service\Report\ValueObject;

/**
 * ValueObject représentant une période de rapport
 *
 * SOLID Principle: Single Responsibility (SRP)
 * - Responsabilité unique: encapsuler les règles métier d'une période
 *
 * ValueObject car:
 * - Possède des INVARIANTS métier (from < to, max 1 an)
 * - IMMUTABLE (pas de setters)
 * - Définit l'égalité par valeur
 */
final class ReportPeriod
{
    private const MAX_PERIOD_DAYS = 365;

    private \DateTimeImmutable $from;
    private \DateTimeImmutable $to;

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(\DateTimeInterface $from, \DateTimeInterface $to)
    {
        $this->from = \DateTimeImmutable::createFromInterface($from)->setTime(0, 0, 0);
        $this->to = \DateTimeImmutable::createFromInterface($to)->setTime(23, 59, 59);

        $this->validate();
    }

    private function validate(): void
    {
        // Règle métier 1: from DOIT être avant to
        if ($this->from > $this->to) {
            throw new \InvalidArgumentException(
                "La date de début ({$this->from->format('Y-m-d')}) doit être avant la date de fin ({$this->to->format('Y-m-d')})"
            );
        }

        // Règle métier 2: période maximale de 1 an
        $daysDiff = $this->from->diff($this->to)->days;
        if ($daysDiff > self::MAX_PERIOD_DAYS) {
            throw new \InvalidArgumentException(
                "La période ne peut pas dépasser " . self::MAX_PERIOD_DAYS . " jours (actuellement: {$daysDiff} jours)"
            );
        }
    }

    public function getFrom(): \DateTimeImmutable
    {
        return $this->from;
    }

    public function getTo(): \DateTimeImmutable
    {
        return $this->to;
    }

    public function getDurationInDays(): int
    {
        return (int) $this->from->diff($this->to)->days;
    }

    public function getFormattedPeriod(): string
    {
        return sprintf(
            'Du %s au %s',
            $this->from->format('d/m/Y'),
            $this->to->format('d/m/Y')
        );
    }

    /**
     * Égalité par valeur (caractéristique ValueObject)
     */
    public function equals(self $other): bool
    {
        return $this->from == $other->from && $this->to == $other->to;
    }
}