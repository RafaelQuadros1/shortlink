# Development Guide

This guide provides detailed information for developers working on ShortLink.

## Table of Contents

- [Environment Setup](#environment-setup)
- [Project Architecture](#project-architecture)
- [Database Schema](#database-schema)
- [API Documentation](#api-documentation)
- [Common Tasks](#common-tasks)
- [Debugging](#debugging)
- [Performance Optimization](#performance-optimization)

## Environment Setup

### Initial Setup

```bash
# Clone and navigate
git clone https://github.com/RafaelQuadros1/shortlink.git
cd shortlink

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate

# Build frontend assets
npm run build

# Start development
composer run dev
```

### Environment Variables

**Key variables for development:**

```env
# Application
APP_NAME=ShortLink
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite
DB_DATABASE=./database/database.sqlite

# OAuth (configure with your providers)
GITHUB_CLIENT_ID=your_id
GITHUB_CLIENT_SECRET=your_secret

GOOGLE_CLIENT_ID=your_id
GOOGLE_CLIENT_SECRET=your_secret

# Session
SESSION_DRIVER=cookie
SESSION_ENCRYPT=false
SESSION_SECURE_COOKIES=false
SESSION_HTTP_ONLY=true
```

## Project Architecture

### Directory Structure

```
app/
├── Console/              # Artisan commands
├── Http/
│   ├── Controllers/      # Request handlers
│   │   ├── ShortController.php
│   │   └── SocialAuthController.php
│   ├── Middleware/       # HTTP middleware
│   │   └── SecurityHeadersMiddleware.php
│   └── Requests/         # Form request validation
├── Models/               # Eloquent models
│   ├── User.php
│   ├── Short.php
│   └── Click.php
├── Observers/            # Eloquent observers
├── Policies/             # Authorization policies
│   └── ShortPolicy.php
├── Services/             # Business logic
│   ├── InputValidator.php
│   └── UrlGenerator.php
└── Jobs/                 # Queued jobs

database/
├── migrations/           # Schema definitions
├── factories/            # Model factories
│   ├── UserFactory.php
│   ├── ShortFactory.php
│   └── ClickFactory.php
└── seeders/              # Database seeders

resources/
├── views/                # Blade templates
│   ├── app.blade.php
│   ├── shorts/
│   ├── legal/
│   └── layouts/
└── css/
    └── app.css           # Tailwind CSS

routes/
└── web.php               # Web routes

tests/
├── Feature/              # Feature tests
│   ├── ShortControllerTest.php
│   └── AuthenticationTest.php
└── Unit/                 # Unit tests
    └── InputValidatorTest.php
```

### Key Models

**User Model**
- Authenticates users via OAuth
- Has many Short links
- `id`, `name`, `email`, `oauth_provider`, `oauth_id`

**Short Model**
- Represents a shortened URL
- Belongs to User
- Has many Clicks
- `id`, `url`, `short_code`, `user_id`, `created_at`

**Click Model**
- Tracks redirects
- Belongs to Short
- `id`, `short_id`, `created_at`

### Security Architecture

**SecurityHeadersMiddleware**
- Adds CSP (Content Security Policy)
- Adds HSTS (HTTP Strict Transport Security)
- Adds X-Frame-Options
- Adds X-Content-Type-Options

**Rate Limiting**
- Authentication: 30 requests/minute per IP
- Redirect: 300 requests/minute per IP

**Input Validation**
- URL validation via InputValidator service
- Short code validation
- XSS prevention

## Database Schema

### users table
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    oauth_provider VARCHAR(255),
    oauth_id VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### shorts table
```sql
CREATE TABLE shorts (
    id BIGINT PRIMARY KEY,
    url LONGTEXT,
    short_code VARCHAR(255) UNIQUE,
    user_id BIGINT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### clicks table
```sql
CREATE TABLE clicks (
    id BIGINT PRIMARY KEY,
    short_id BIGINT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (short_id) REFERENCES shorts(id)
);
```

View actual migrations:
```bash
ls database/migrations/
```

## API Documentation

### Authentication Routes

**GitHub OAuth**
- Redirect: `GET /auth/github/redirect`
- Callback: `GET /auth/github/callback`

**Google OAuth**
- Redirect: `GET /auth/google/redirect`
- Callback: `GET /auth/google/callback`

**Logout**
- `POST /logout` - Requires authentication

### Short Link Routes

**Create Short Link**
```http
POST /shorts
Authorization: ******
Content-Type: application/json

{
    "url": "https://example.com/very-long-url"
}
```

**Get All Short Links**
```http
GET /shorts
Authorization: ******
```

**View Short Link**
```http
GET /shorts/{id}
Authorization: ******
```

**Update Short Link**
```http
PUT /shorts/{id}
Authorization: ******

{
    "url": "https://example.com/new-url"
}
```

**Delete Short Link**
```http
DELETE /shorts/{id}
Authorization: ******
```

**Redirect to Original URL**
```http
GET /{short_code}
```

## Common Tasks

### Creating a New Feature

1. **Create Migration**
   ```bash
   php artisan make:migration create_table_name --create=table_name
   ```

2. **Create Model**
   ```bash
   php artisan make:model ModelName -m
   ```

3. **Create Controller**
   ```bash
   php artisan make:controller ControllerName --resource
   ```

4. **Create Test**
   ```bash
   php artisan make:test FeatureTest --pest
   ```

5. **Define Routes**
   - Edit `routes/web.php`

6. **Add Tests**
   - Create tests in `tests/Feature/` or `tests/Unit/`

7. **Run Tests**
   ```bash
   php artisan test --compact
   ```

### Running Queue Jobs

```bash
# Listen for jobs
php artisan queue:listen

# Process a single job
php artisan queue:work --once
```

### Database Commands

```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Reset database
php artisan migrate:reset

# Fresh database (rollback + migrate)
php artisan migrate:fresh

# Seed database
php artisan db:seed

# Seed with specific seeder
php artisan db:seed --class=UserSeeder
```

### Tinker (Interactive Shell)

```bash
# Enter interactive shell
php artisan tinker

# Create a user
$user = User::factory()->create();

# Query shorts
Short::where('user_id', 1)->get();

# Exit
exit
```

### Cache Management

```bash
# Clear all cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Cache config
php artisan config:cache
```

## Debugging

### Laravel Debugbar

The project includes Laravel Debugbar for development. It appears at the bottom of every page when `APP_DEBUG=true`.

Features:
- Query profiling
- Route information
- View rendering time
- Timeline events

### Logging

Logs are stored in `storage/logs/`. View recent logs:

```bash
# Tail logs in real-time
tail -f storage/logs/laravel.log

# View last 50 lines
tail -50 storage/logs/laravel.log

# Security logs
tail -f storage/logs/security.log
```

### Debugging with Xdebug

Configure your IDE (VS Code, PhpStorm, etc.) with Xdebug settings. Breakpoints work in:
- Controllers
- Models
- Service classes
- Tests

### Browser Console

View browser console logs (F12):
- JavaScript errors
- Network requests
- Console messages

## Performance Optimization

### Database Optimization

```php
// Use eager loading to prevent N+1 queries
Short::with('user', 'clicks')->get();

// Use select to fetch only needed columns
Short::select('id', 'short_code', 'url')->get();

// Add database indexes
Schema::table('shorts', function (Blueprint $table) {
    $table->index('short_code');
    $table->index('user_id');
});
```

### Caching

```php
// Cache query results
$shorts = Cache::remember('user.shorts.' . $user->id, 3600, function () use ($user) {
    return $user->shorts()->get();
});

// Forget cache when updated
Cache::forget('user.shorts.' . $user->id);
```

### Query Optimization

```bash
# Check query performance
php artisan tinker
> DB::enableQueryLog();
> Short::with('clicks')->get();
> dd(DB::getQueryLog());
```

### Asset Optimization

```bash
# Build optimized assets for production
npm run build

# Development with watch
npm run dev
```

## Tips & Tricks

### Quick Testing

```bash
# Test only changed files
php artisan test --compact --filter=YourTest

# Stop on first failure
php artisan test --compact --stop-on-failure
```

### Code Quality

```bash
# Run Pint formatter
vendor/bin/pint --dirty

# Check for security vulnerabilities
composer audit

# Analyze code with PHPStan
vendor/bin/phpstan
```

### Git Workflow

```bash
# See unpushed commits
git log --oneline origin/main..HEAD

# Rebase with main
git rebase origin/main

# Force push after rebase (use carefully!)
git push origin branch-name --force-with-lease
```

---

For more information, see:
- [Laravel Documentation](https://laravel.com/docs)
- [Pest Documentation](https://pestphp.com)
- [README.md](README.md)
- [CONTRIBUTING.md](CONTRIBUTING.md)
- [SECURITY.md](SECURITY.md)
