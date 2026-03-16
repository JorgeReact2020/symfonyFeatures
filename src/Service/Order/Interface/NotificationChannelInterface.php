<?php

declare(strict_types=1);

namespace App\Service\Order\Interface;

use App\Service\Order\DTO\OrderConfirmationDTO;

interface NotificationChannelInterface
{
    public function send(OrderConfirmationDTO $confirmation, string $recipient): void;
    
    public function getName(): string;
    
    public function isAvailableFor(string $recipient): bool;
}
