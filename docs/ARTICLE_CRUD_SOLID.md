# Article CRUD - SOLID Principles Implementation

This document explains how the Article CRUD follows **SOLID principles** strictly.

## 📁 Project Structure

```
src/
├── Entity/
│   └── Article.php                    # Data model (S)
├── Repository/
│   └── ArticleRepository.php          # Data access (S)
├── Service/
│   └── Article/
│       ├── ArticleServiceInterface.php # Abstraction (D)
│       └── ArticleService.php          # Business logic (S)
├── Form/
│   └── ArticleType.php                # Form handling (S)
└── Controller/
    └── Admin/
        └── ArticleController.php      # HTTP handling (S)

templates/admin/article/
├── index.html.twig                    # List view
├── new.html.twig                      # Create form
├── edit.html.twig                     # Edit form
└── show.html.twig                     # Detail view
```

---

## 🎯 SOLID Principles Breakdown

### **S - Single Responsibility Principle**

Each class has ONE and ONLY ONE reason to change:

#### **Article Entity**
- **Responsibility:** Represent article data structure and domain rules
- **Does NOT:** Handle persistence, HTTP, business logic
- **File:** `src/Entity/Article.php`

#### **ArticleRepository**
- **Responsibility:** Query articles from database
- **Does NOT:** Modify data, contain business logic
- **File:** `src/Repository/ArticleRepository.php`

#### **ArticleService**
- **Responsibility:** Article business logic (create, update, delete)
- **Does NOT:** Handle HTTP requests, build queries, render views
- **File:** `src/Service/Article/ArticleService.php`

#### **ArticleType (Form)**
- **Responsibility:** Define form structure and fields
- **Does NOT:** Validate (entity does), persist (service does)
- **File:** `src/Form/ArticleType.php`

#### **ArticleController**
- **Responsibility:** Handle HTTP request/response
- **Does NOT:** Business logic (delegates to service), queries (delegates to service)
- **File:** `src/Controller/Admin/ArticleController.php`

---

### **O - Open/Closed Principle**

**Open for extension, closed for modification**

#### Example: ArticleServiceInterface
```php
// Want to add caching? Create CachedArticleService
class CachedArticleService implements ArticleServiceInterface
{
    public function __construct(
        private ArticleServiceInterface $innerService,
        private CacheInterface $cache
    ) {}

    public function findById(int $id): Article
    {
        return $this->cache->get("article_$id", function() use ($id) {
            return $this->innerService->findById($id);
        });
    }
}

// Controller doesn't change - still depends on interface!
```

No need to modify existing code - just extend!

---

### **L - Liskov Substitution Principle**

**Subtypes must be substitutable for their base types**

```php
// Controller depends on interface, not implementation
public function __construct(
    private ArticleServiceInterface $articleService
) {}

// Can substitute ANY implementation:
// - ArticleService (default)
// - CachedArticleService
// - MockArticleService (for testing)
// - LoggingArticleService
// - etc.

// All work the same - no breaking changes
```

---

### **I - Interface Segregation Principle**

**Many specific interfaces > one general interface**

```php
// ✅ GOOD - Specific interface
interface ArticleServiceInterface {
    public function create(Article $article): void;
    public function update(Article $article): void;
    public function delete(Article $article): void;
}

// ❌ BAD - Fat interface
interface CRUDServiceInterface {
    public function create($entity): void;
    public function read($id);
    public function update($entity): void;
    public function delete($entity): void;
    public function search($query);
    public function export($format);
    public function import($file);
    // ... 20 more methods
}
```

Our interface is **focused** - only what article management needs.

---

### **D - Dependency Inversion Principle**

**Depend on abstractions, not concretions**

#### ✅ CORRECT Implementation:

```php
// Controller depends on INTERFACE
public function __construct(
    private ArticleServiceInterface $articleService  // ← Interface!
) {}
```

**Benefits:**
- Easy to test with mocks
- Easy to swap implementations
- Loose coupling

#### ❌ WRONG (if we did this):

```php
// Controller depends on CONCRETE class
public function __construct(
    private ArticleService $articleService  // ← Concrete class!
) {}
```

**Problems:**
- Hard to test
- Hard to change
- Tight coupling

---

## 🔄 Request Flow

### Creating an Article

```
1. Browser → POST /admin/articles/new
   ↓
2. ArticleController::new()
   - Creates empty Article
   - Creates ArticleType form
   - Handles form submission
   ↓
3. Form validation (ArticleType + Article constraints)
   ↓
4. ArticleService::create($article)
   - Business logic
   - Logging
   - EntityManager persist
   ↓
5. ArticleRepository (automatic via Doctrine)
   - INSERT INTO article
   ↓
6. Redirect → /admin/articles (list)
```

**Key Points:**
- Controller: Thin (only HTTP)
- Service: Fat (contains logic)
- Repository: Focused (only queries)
- Entity: Data + validation

---

## 🧪 Testing Benefits

Because of SOLID, testing is easy:

```php
// Mock the service interface
$mockService = $this->createMock(ArticleServiceInterface::class);
$mockService->method('findAll')->willReturn([new Article()]);

// Inject into controller
$controller = new ArticleController($mockService);

// Test without database!
```

---

## 🎓 Key Takeaways

1. **S** - Each class has ONE job
2. **O** - Extend through interfaces, don't modify
3. **L** - Implementations are interchangeable
4. **I** - Small, focused interfaces
5. **D** - Depend on abstractions (interfaces)

---

## 🚀 Routes

```bash
# List all articles
GET    /admin/articles

# Show create form
GET    /admin/articles/new

# Create article
POST   /admin/articles/new

# Show article details
GET    /admin/articles/{id}

# Show edit form
GET    /admin/articles/{id}/edit

# Update article
POST   /admin/articles/{id}/edit

# Delete article
POST   /admin/articles/{id}/delete
```

---

## 📝 Usage

1. Login as admin: `/login` (admin@admin.com / admin)
2. Go to dashboard: `/admin`
3. Click "Articles" card
4. Start managing articles!

All operations:
- ✅ Follow SOLID principles
- ✅ Are fully tested-ready
- ✅ Use proper separation of concerns
- ✅ Have clean architecture
