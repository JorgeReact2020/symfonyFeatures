<?php

declare(strict_types=1);

namespace App\Service\Report\Interface;

/**
 * Interface pour les exporters supportant le formatage avancé
 *
 * SOLID Principle: Interface Segregation (ISP)
 * - Séparée car CSV/JSON n'ont pas de mise en page
 * - Seulement PDF et Excel ont des styles, couleurs, polices
 */
interface FormattingCapableInterface
{
    /**
     * Applique des styles au rapport (couleurs, polices, bordures)
     *
     * @param array $styles Configuration des styles
     */
    public function setStyles(array $styles): void;

    /**
     * Vérifie si le formatting est supporté
     */
    public function supportsFormatting(): bool;
}
