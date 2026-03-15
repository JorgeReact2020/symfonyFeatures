# 🎯 Système de Notification SOLID

Une implémentation complète et stricte des 5 principes SOLID en PHP 8.3+ avec Symfony.

## 📁 Structure du Projet

```
src/Service/Notification/
├── Interface/
│   ├── NotificationChannelInterface.php      # Interface pour les canaux
│   ├── MessageFormatterInterface.php          # Interface pour le formatage
│   ├── NotificationLoggerInterface.php        # Interface pour le logging
│   └── NotificationFilterInterface.php        # Interface pour le filtrage
├── DTO/
│   └── NotificationMessage.php                # DTO immutable pour les données
├── Channel/
│   ├── EmailChannel.php                       # Implémentation Email
│   ├── SmsChannel.php                         # Implémentation SMS
│   └── SlackChannel.php                       # Implémentation Slack
├── Formatter/
│   ├── EmailFormatter.php                     # Formatage HTML
│   ├── SmsFormatter.php                       # Formatage texte court
│   └── SlackFormatter.php                     # Formatage Markdown
├── Logger/
│   └── NotificationLogger.php                 # Logging des notifications
├── Filter/
│   └── UserPreferenceFilter.php               # Filtrage par préférences
└── NotificationService.php                    # Service orchestrateur
```

## ✅ Application des Principes SOLID

### **S - Single Responsibility Principle**

Chaque classe a UNE SEULE raison de changer :

| Classe | Responsabilité Unique |
|--------|----------------------|
| `EmailChannel` | Envoyer des emails (et rien d'autre) |
| `EmailFormatter` | Formater en HTML (ne logue pas, n'envoie pas) |
| `NotificationLogger` | Logger les événements (ne formate pas, n'envoie pas) |
| `UserPreferenceFilter` | Filtrer selon préférences (ne logue pas, n'envoie pas) |
| `NotificationMessage` | Transporter des données (aucune logique métier) |

**Exemple concret :**
```php
// ❌ AVANT (violait SRP) - Faisait 3 choses
class EmailNotification {
    public function send() {
        $this->logger->info("Sending...");  // 1. Log
        $this->emailService->send(...);      // 2. Envoi
        $this->logger->info("Sent");         // 3. Log
    }
}

// ✅ APRÈS (respecte SRP) - Une seule responsabilité
class EmailChannel implements NotificationChannelInterface {
    public function send(NotificationMessage $message): bool {
        $formatted = $this->formatter->format($message);
        // Envoie uniquement, le logging est fait ailleurs
        return $this->doSend($formatted);
    }
}
```

### **O - Open/Closed Principle**

Le système est **ouvert à l'extension, fermé à la modification**.

**Test pratique :** Ajouter WhatsApp sans toucher au code existant

1. **Créer le nouveau formatter :**
```php
// src/Service/Notification/Formatter/WhatsAppFormatter.php
class WhatsAppFormatter implements MessageFormatterInterface {
    public function format(NotificationMessage $message): string {
        return "🟢 {$message->title}\n\n{$message->content}";
    }
}
```

2. **Créer le nouveau channel :**
```php
// src/Service/Notification/Channel/WhatsAppChannel.php
class WhatsAppChannel implements NotificationChannelInterface {
    public function __construct(
        private readonly MessageFormatterInterface $formatter
    ) {}
    
    public function send(NotificationMessage $message): bool {
        $formatted = $this->formatter->format($message);
        // Logique d'envoi WhatsApp...
        return true;
    }
    
    public function getName(): string {
        return 'whatsapp';
    }
}
```

3. **Configurer dans services.yaml :**
```yaml
App\Service\Notification\Channel\WhatsAppChannel:
    arguments:
        $formatter: '@App\Service\Notification\Formatter\WhatsAppFormatter'
    tags: ['app.notification_channel']
```

**✅ Aucune modification de `NotificationService` nécessaire !**

### **L - Liskov Substitution Principle**

Tous les channels sont **interchangeables** sans changer le comportement attendu.

```php
// Ces trois appels fonctionnent exactement de la même manière
$service->send($message, 'email');   // ✅ Fonctionne
$service->send($message, 'sms');     // ✅ Fonctionne
$service->send($message, 'slack');   // ✅ Fonctionne
```

**Contrat respecté :**
- Tous retournent `bool` (succès/échec)
- Tous acceptent `NotificationMessage`
- Tous ont la méthode `getName()`
- Aucun ne lève d'exception inattendue

**Test :**
```php
// On peut remplacer n'importe quel channel par un autre
function processNotification(NotificationChannelInterface $channel, NotificationMessage $msg) {
    return $channel->send($msg);  // Fonctionne avec TOUS les channels
}
```

### **I - Interface Segregation Principle**

Les interfaces sont **petites et ciblées**, pas de méthodes inutiles.

#### ❌ Avant (violation) :
```php
interface SendNotificationInterface {
    public function getContent(): string;           // Pourquoi dans l'interface ?
    public function getRecipient(): string;         // Pourquoi dans l'interface ?
    public function send(): void;
    public function logNotification(): void;        // Mélange des responsabilités
    public function setRecipient(string $email): void;  // Mutabilité forcée
    public function setContent(string $content): void;  // Mutabilité forcée
}
```

#### ✅ Après (respecte ISP) :
```php
// Interface 1 : Envoi uniquement (2 méthodes)
interface NotificationChannelInterface {
    public function send(NotificationMessage $message): bool;
    public function getName(): string;
}

// Interface 2 : Formatage uniquement (1 méthode)
interface MessageFormatterInterface {
    public function format(NotificationMessage $message): string;
}

// Interface 3 : Logging uniquement (2 méthodes)
interface NotificationLoggerInterface {
    public function logSuccess(NotificationMessage $message, string $channel): void;
    public function logFailure(NotificationMessage $message, string $channel, string $reason): void;
}

// Interface 4 : Filtrage uniquement (1 méthode)
interface NotificationFilterInterface {
    public function canSend(NotificationMessage $message, string $channel): bool;
}
```

**Avantages :**
- Chaque client implémente uniquement ce dont il a besoin
- Pas de méthodes vides ou non implémentées
- Clarté du rôle de chaque interface

### **D - Dependency Inversion Principle**

Le système dépend d'**abstractions**, pas de classes concrètes.

#### Exemples de dépendances inversées :

```php
// ✅ EmailChannel dépend de MessageFormatterInterface (abstraction)
class EmailChannel implements NotificationChannelInterface {
    public function __construct(
        private readonly MessageFormatterInterface $formatter  // ✅ Interface, pas EmailFormatter
    ) {}
}

// ✅ NotificationService dépend d'interfaces uniquement
class NotificationService {
    public function __construct(
        private readonly iterable $channels,                        // ✅ Collection d'interfaces
        private readonly NotificationFilterInterface $filter,       // ✅ Interface
        private readonly NotificationLoggerInterface $logger        // ✅ Interface
    ) {}
}

// ✅ NotificationLogger dépend de Psr\Log\LoggerInterface
class NotificationLogger implements NotificationLoggerInterface {
    public function __construct(
        private readonly LoggerInterface $logger  // ✅ Interface PSR-3
    ) {}
}
```

#### ❌ Avant (violation) :
```php
class EmailNotification {
    public function __construct(
        private LoggerInterface $logger,        // ✅ Interface
        private EmailService $emailService      // ❌ Classe concrète !
    ) {}
}
```

#### ✅ Après :
```php
interface EmailSenderInterface {
    public function send(string $to, string $subject, string $body): bool;
}

class EmailService implements EmailSenderInterface { ... }

class EmailChannel {
    public function __construct(
        private readonly MessageFormatterInterface $formatter,  // ✅ Abstraction
        private readonly EmailSenderInterface $sender           // ✅ Abstraction
    ) {}
}
```

## 🧪 Tests Disponibles

Démarre le serveur :
```bash
symfony server:start
```

Puis accède à : `http://localhost:8000/notification`

### Test 1 : Envoi Simple
- **URL :** `/notification/test/simple`
- **Vérifie :** Single Responsibility (chaque classe fait une chose)
- **Résultat attendu :** Email formaté en HTML envoyé avec succès

### Test 2 : Multi-Canal
- **URL :** `/notification/test/multi-channel`
- **Vérifie :** Liskov Substitution (tous les canaux interchangeables)
- **Résultat attendu :** Email + SMS + Slack envoyés

### Test 3 : Préférences Utilisateur
- **URL :** `/notification/test/preferences`
- **Vérifie :** Single Responsibility (filtrage séparé de l'envoi)
- **Résultat attendu :** Email ✅, SMS ❌ (bloqué), Slack ✅

### Test 4 : Extension (Open/Closed)
- **URL :** `/notification/test/open-closed`
- **Vérifie :** Open/Closed Principle
- **Résultat attendu :** Documentation pour ajouter WhatsApp sans modification

## 📊 Checklist de Validation SOLID

### ✅ Single Responsibility
- [x] Chaque classe a un seul rôle clair
- [x] Pas de méthodes qui font plusieurs choses
- [x] Les noms de classes reflètent leur unique responsabilité

### ✅ Open/Closed
- [x] Peut ajouter WhatsApp en créant juste une nouvelle classe
- [x] Peut ajouter un nouveau format sans modifier les existants
- [x] Aucun `if/switch` sur des types concrets dans NotificationService

### ✅ Liskov Substitution
- [x] Toutes les implémentations de `NotificationChannelInterface` sont interchangeables
- [x] Aucune exception inattendue dans les sous-classes
- [x] Le contrat de l'interface est respecté partout

### ✅ Interface Segregation
- [x] Interfaces petites et ciblées (1-2 méthodes chacune)
- [x] Pas de méthodes vides ou non implémentées
- [x] Chaque interface a un rôle spécifique

### ✅ Dependency Inversion
- [x] Aucune dépendance vers des classes concrètes (sauf DTOs)
- [x] Tout est injecté via le constructeur
- [x] Les interfaces sont dans un namespace séparé

## 🎓 Points d'Apprentissage Clés

1. **DTO Immutable :** `NotificationMessage` est `readonly`, garantit l'intégrité des données
2. **Tagged Iterator :** Symfony injecte automatiquement tous les channels via le tag
3. **Composition over Inheritance :** Aucune classe n'hérite d'une autre (sauf AbstractController)
4. **Type Safety :** PHP 8.3 `readonly`, `declare(strict_types=1)`, types stricts partout
5. **PSR Standards :** Utilise `Psr\Log\LoggerInterface` au lieu de créer sa propre interface

## 🚀 Prochaines Étapes (Bonus)

- [ ] Ajouter un système de retry avec `RetryChannel` (decorator pattern)
- [ ] Implémenter un rate limiter (`RateLimitFilter`)
- [ ] Créer des événements Symfony (`NotificationSentEvent`)
- [ ] Ajouter des tests unitaires PHPUnit
- [ ] Implémenter un queue system avec Messenger

## 📝 Conclusion

Ce système démontre que les principes SOLID ne sont pas théoriques mais **pratiques et bénéfiques** :

- ✅ **Maintenabilité** : Facile de comprendre où faire les changements
- ✅ **Testabilité** : Chaque classe peut être testée indépendamment
- ✅ **Extensibilité** : Ajouter WhatsApp prend 5 minutes
- ✅ **Robustesse** : Les interfaces garantissent les contrats
- ✅ **Clarté** : Le code est auto-documenté par sa structure

---

**Créé pour l'exercice SOLID - Mars 2026** 🎯
