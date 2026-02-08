# Event System Examples

This project demonstrates both **Event Listeners** and **Event Subscribers** in Symfony.

## What Was Implemented

### 1. Event Listener
**File:** `src/EventListener/RequestLoggerListener.php`
**Configuration:** `config/services.yaml`

- Listens to ONE event: `kernel.request`
- Requires manual configuration with tags
- Logs basic request information

### 2. Event Subscriber
**File:** `src/EventSubscriber/HttpLifecycleSubscriber.php`
**Configuration:** Automatic (autoconfigure)

- Listens to THREE events: `kernel.request`, `kernel.response`, `kernel.exception`
- No manual configuration needed
- Adds custom headers to responses
- Tracks requests with unique IDs
- Logs the full request/response lifecycle

## How to Test

### 1. Check Logs
```bash
tail -f var/log/dev.log
```

### 2. Visit Any Page
```bash
# Visit home page
curl -I http://localhost:8000/home

# Visit admin (logged in)
curl -I http://localhost:8000/admin

# Visit API
curl -I http://localhost:8000/api/health
```

### 3. Check Response Headers
Look for these custom headers added by the Event Subscriber:
- `X-Request-ID: req_xxxxx`
- `X-Powered-By: Symfony 8`
- `X-Custom-Header: Event-Subscriber-Example`

### 4. Check Logs Output
You should see:
```
[EVENT LISTENER] Request received - method: GET, path: /home
[EVENT SUBSCRIBER] Request started - request_id: req_xxxxx
[EVENT SUBSCRIBER] Response prepared - status_code: 200
```

## Key Differences

| Feature | Event Listener | Event Subscriber |
|---------|---------------|------------------|
| **File** | `EventListener/RequestLoggerListener.php` | `EventSubscriber/HttpLifecycleSubscriber.php` |
| **Events** | 1 event | 3 events |
| **Configuration** | Manual (services.yaml) | Automatic |
| **Interface** | None | `EventSubscriberInterface` |
| **Priority** | In yaml config | In `getSubscribedEvents()` |

## Execution Order

When you visit `/home`:
1. `RequestLoggerListener::onKernelRequest()` - Priority default (0)
2. `HttpLifecycleSubscriber::onKernelRequest()` - Priority 10 (runs first!)
3. Controller executes
4. `HttpLifecycleSubscriber::onKernelResponse()` - Adds headers
5. Response sent to browser

## Learn More

- [Symfony Events Documentation](https://symfony.com/doc/current/event_dispatcher.html)
- [Creating Event Listeners](https://symfony.com/doc/current/event_dispatcher.html#creating-an-event-listener)
- [Creating Event Subscribers](https://symfony.com/doc/current/event_dispatcher.html#creating-an-event-subscriber)
