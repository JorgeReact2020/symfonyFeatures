<?php

declare(strict_types=1);

namespace App\Service\Order\Exception;

class InvalidPromotionCodeException extends \RuntimeException
{
    public function __construct(string $code)
    {
        parent::__construct(sprintf('Invalid or expired promotion code: "%s"', $code));
    }
}
