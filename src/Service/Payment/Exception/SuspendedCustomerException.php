<?php

declare(strict_types=1);

namespace App\Service\Payment\Exception;

/**
 * Exception levée quand un client a un compte suspendu
 */
class SuspendedCustomerException extends PaymentException
{
    public function __construct(string $customerId)
    {
        parent::__construct("Le compte client '{$customerId}' est suspendu et ne peut pas effectuer de paiements");
    }
}
