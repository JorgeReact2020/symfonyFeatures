<?php

namespace App\Service\Api;

use Symfony\Component\HttpFoundation\Response;

class HealthService
{
    public function __invoke(): string
    {
        $timestamp = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
        $status = Response::HTTP_OK;

        return <<<HTML
        <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded'>
            <strong class='font-bold'>✓ Health Check Successful</strong>
            <div class='mt-2 text-sm'>
                <p><strong>Status:</strong> $status</p>
                <p><strong>Service:</strong> $timestamp</p>
            </div>
                </div>
            </div>
        </div>
HTML;
    }
}
