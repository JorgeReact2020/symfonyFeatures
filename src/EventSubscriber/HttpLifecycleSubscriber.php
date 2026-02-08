<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Event Subscriber - Advanced approach
 * Listens to MULTIPLE events: request, response, exception
 * Automatically registered (no manual configuration needed!)
 */
class HttpLifecycleSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    /**
     * This method tells Symfony which events to listen to
     * Returns an array: [event_name => method_to_call]
     * Can also specify priority: [method_to_call, priority]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 10],    // Priority 10 (higher = earlier)
            KernelEvents::RESPONSE => 'onKernelResponse',        // Default priority 0
            KernelEvents::EXCEPTION => ['onKernelException', 5], // Priority 5
        ];
    }

    /**
     * Called when kernel.request event fires
     * Adds custom header to track request ID
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        // Add a unique request ID to the request attributes
        $requestId = uniqid('req_', true);
        $request->attributes->set('request_id', $requestId);

        $this->logger->info('📨 [EVENT SUBSCRIBER] Request started', [
            'request_id' => $requestId,
            'method' => $request->getMethod(),
            'path' => $request->getPathInfo(),
        ]);
    }

    /**
     * Called when kernel.response event fires
     * Adds custom headers to all responses
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $response = $event->getResponse();

        // Get request ID from request attributes
        $requestId = $request->attributes->get('request_id', 'unknown');

        // Add custom headers to response
        $response->headers->set('X-Request-ID', $requestId);
        $response->headers->set('X-Powered-By', 'Symfony 8');
        $response->headers->set('X-Custom-Header', 'Event-Subscriber-Example');

        $this->logger->info('📤 [EVENT SUBSCRIBER] Response prepared', [
            'request_id' => $requestId,
            'status_code' => $response->getStatusCode(),
        ]);
    }

    /**
     * Called when an exception is thrown during request handling
     * Logs error details
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();
        $requestId = $request->attributes->get('request_id', 'unknown');

        $this->logger->error('❌ [EVENT SUBSCRIBER] Exception occurred', [
            'request_id' => $requestId,
            'exception' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]);

        // You could also modify the response here
        // For example, create a custom error page
        // $response = new Response('Custom error page', 500);
        // $event->setResponse($response);
    }
}
