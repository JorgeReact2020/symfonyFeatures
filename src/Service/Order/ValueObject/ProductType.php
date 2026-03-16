<?php

declare(strict_types=1);

namespace App\Service\Order\ValueObject;

enum ProductType: string
{
    case PHYSICAL = 'physical';
    case DIGITAL = 'digital';
    case SERVICE = 'service';
}
