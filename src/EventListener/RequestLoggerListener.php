<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Event Listener - Simple approach
 * Listens to ONE event: kernel.request
 * Logs information about incoming requests
 */
class RequestLoggerListener
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    /**
     * This method will be called when kernel.request event fires
     * BEFORE any controller is executed
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        // Only handle main request (not sub-requests)
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        
        $this->logger->info('🎯 [EVENT LISTENER] Request received', [
            'method' => $request->getMethod(),
            'path' => $request->getPathInfo(),
            'ip' => $request->getClientIp(),
        ]);
    }
}
