<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Psr\Log\LoggerInterface;

/**
 * Example Event Subscriber - Zero configuration needed!
 * This will log every HTTP request automatically.
 */
class TestSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    /**
     * This method tells Symfony which events to subscribe to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onRequest',  // Called on every request
        ];
    }

    /**
     * This method is called automatically on every HTTP request
     */
    public function onRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $this->logger->info('🔔 [TEST SUBSCRIBER] Request received', [
            'path' => $request->getPathInfo(),
            'method' => $request->getMethod(),
        ]);
    }
}
