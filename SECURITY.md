# Security Implementation Guide

This document outlines all the security measures implemented in the ShortLink application to protect user data and prevent common web vulnerabilities.

## Table of Contents

1. [Environment & Configuration Security](#environment--configuration-security)
2. [Authentication & Authorization](#authentication--authorization)
3. [Security Headers](#security-headers)
4. [Input Validation & XSS Prevention](#input-validation--xss-prevention)
5. [Rate Limiting](#rate-limiting)
6. [Social Authentication Security](#social-authentication-security)
7. [Database Security](#database-security)
8. [Logging & Monitoring](#logging--monitoring)
9. [CI/CD Security](#cicd-security)
10. [Best Practices](#best-practices)

---

## Environment & Configuration Security

### Configuration Files

- **`.env.production`**: Production environment template with secure defaults
  - `APP_DEBUG=false` - Disable debug mode in production
  - `SESSION_ENCRYPT=true` - Encrypt session data
  - `SESSION_SECURE_COOKIES=true` - HTTPS-only cookies
  - `SESSION_HTTP_ONLY=true` - Prevent JavaScript access to cookies
  - `SESSION_SAME_SITE=lax` - CSRF protection

### Environment Variables

Never commit `.env` files. Use `.env.production` as a template for production deployment.

```bash
# Copy and configure for production
cp .env.production .env
# Edit .env with production values
```

Key security variables:
- `APP_KEY` - Must be set (run `php artisan key:generate`)
- `APP_ENV` - Set to `production` in production
- `APP_DEBUG` - Must be `false` in production
- OAuth credentials - Store securely, never commit to repository

---

## Authentication & Authorization

### Authorization Policies

The application uses Laravel policies to enforce authorization:

```php
// app/Policies/ShortPolicy.php
- Users can only view their own short links
- Only authenticated users can create short links
- Only the owner can update or delete their links
```

### Authorization Validation

All endpoints requiring authentication use the `auth` middleware:

```php
Route::resource('shorts', ShortController::class)
    ->middleware('auth');
```

Form Requests validate authorization before processing:

```php
// StoreShortRequest
public function authorize(): bool
{
    return auth()->check();
}
```

### Session Security

Session configuration enforces security best practices:

- **Session Encryption**: Enabled in production
- **HTTP Only**: Prevents JavaScript from accessing session cookies
- **Secure Cookies**: Only transmitted over HTTPS in production
- **Same-Site**: Set to `lax` to prevent CSRF attacks

---

## Security Headers

### Implemented Headers

The `SecurityHeadersMiddleware` adds multiple security headers to every response:

| Header | Value | Purpose |
|--------|-------|---------|
| `X-Frame-Options` | `SAMEORIGIN` | Prevents clickjacking attacks |
| `X-Content-Type-Options` | `nosniff` | Prevents MIME type sniffing |
| `X-XSS-Protection` | `1; mode=block` | Enables XSS protection in browsers |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Controls referrer information |
| `Content-Security-Policy` | Restrictive policy | Prevents inline scripts and XSS |
| `Strict-Transport-Security` | `max-age=63072000` | Enforces HTTPS (production only) |
| `Permissions-Policy` | Restrictive permissions | Disables unnecessary features |

### Content Security Policy (CSP)

The CSP header restricts resource loading:

```
default-src 'self'                    # Only same-origin by default
script-src 'self' cdn.tailwindcss.com # Scripts from self and Tailwind CDN
style-src 'self' cdn.tailwindcss.com  # Styles from self and Tailwind CDN
img-src 'self' data: https:           # Images from self, data URIs, and HTTPS
font-src 'self' data:                 # Fonts from self and data URIs
connect-src 'self'                    # AJAX/fetch only from self
frame-ancestors 'self'                # Can only be framed by self
```

---

## Input Validation & XSS Prevention

### URL Validation

URLs are validated using Laravel's built-in validators:

```php
'url_origin' => ['required', 'url', 'max:2048']
```

Additional validation via `InputValidator` service:
- Only HTTP/HTTPS schemes allowed
- Private IP ranges blocked
- Localhost blocked
- Maximum URL length: 2048 characters

### XSS Prevention

All user input in Blade templates is automatically escaped:

```blade
{!! $variable !!}           <!-- Raw HTML - use sparingly -->
{{ $variable }}             <!-- Escaped HTML - default -->
@{{ $variable }}            <!-- Escaped in JavaScript -->
```

Guidelines:
- Use `{{ }}` for all dynamic content by default
- Only use `{!! !!}` when you trust the content (e.g., internal HTML)
- Never output user input with `{!! !!}`

### Short Code Validation

Short codes are validated using `InputValidator`:

```php
InputValidator::validateShortCode($code)  # Validates format
InputValidator::sanitizeShortCode($code)  # Removes dangerous characters
```

Allowed characters: `a-z`, `A-Z`, `0-9`, `-`, `_`

---

## Rate Limiting

### Rate Limit Middleware

The application implements rate limiting on sensitive endpoints:

| Endpoint | Limit | Purpose |
|----------|-------|---------|
| `POST /shorts` | 30 requests/minute | Prevent link creation spam |
| `GET /{short_code}` | 300 requests/minute | Allow legitimate traffic, prevent abuse |
| `/auth/{provider}/redirect` | 30 requests/minute | Prevent auth abuse |
| `/auth/{provider}/callback` | 30 requests/minute | Prevent callback abuse |

Configure rate limits in `.env`:

```env
RATE_LIMIT_REQUESTS=60
RATE_LIMIT_PERIOD=60
```

### Rate Limit Response

When rate limit is exceeded, the application returns a `429 Too Many Requests` error.

---

## Social Authentication Security

### OAuth Provider Validation

The application validates OAuth providers before processing:

```php
$validProviders = ['github', 'google'];
if (!in_array($provider, $validProviders)) {
    Log::warning('Invalid OAuth provider attempted');
    return back()->with('error', 'Provider inválido.');
}
```

### OAuth Data Validation

User data from OAuth providers is validated:

```php
if (!$socialUser->getId() || !$socialUser->getEmail()) {
    Log::warning('Incomplete OAuth user data');
    return redirect('/')->with('error', 'Dados incompletos...');
}
```

### Error Handling

OAuth errors are caught and logged:

```php
try {
    $socialUser = Socialite::driver($provider)->stateless()->user();
} catch (\Exception $e) {
    Log::error('OAuth authentication failed', [
        'provider' => $provider,
        'error' => $e->getMessage(),
    ]);
}
```

### Remember Token

The `remember: false` parameter is used instead of `remember: true`:

```php
Auth::login($user, remember: false);
```

This prevents persistent login tokens that could be compromised.

---

## Database Security

### Parameterized Queries

All database queries use Eloquent ORM which uses parameterized queries by default:

```php
// Safe - uses parameter binding
$user = User::where('email', $email)->first();

// Safe - Eloquent query builder
$shorts = Short::where('user_id', auth()->id())->get();
```

Never use raw SQL with user input:

```php
// UNSAFE - Never do this
DB::raw("SELECT * FROM users WHERE email = '$email'");

// SAFE - Use parameter binding
DB::select('SELECT * FROM users WHERE email = ?', [$email]);
```

### Database Connection

Configure secure database connections in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shortlink
DB_USERNAME=root
DB_PASSWORD=secure_password
```

### Query Logging

Query logging is configured for non-production environments:

```php
// app/Providers/SecurityServiceProvider.php
if ($this->app->environment('local', 'staging')) {
    $this->app['db']->listen(function ($query) {
        Log::channel('security')->debug('Database Query', [
            'sql' => $query->sql,
            'bindings' => $query->bindings,
        ]);
    });
}
```

---

## Logging & Monitoring

### Security Log Channel

A dedicated security log channel logs important events:

```php
// config/logging.php
'security' => [
    'driver' => 'daily',
    'path' => storage_path('logs/security.log'),
    'level' => 'info',
    'days' => 30,
]
```

### Logged Events

The following events are logged to the security channel:

```php
// User authentication
Log::channel('security')->info('User authenticated via social provider', [
    'user_id' => $user->id,
    'provider' => $provider,
    'ip' => request()->ip(),
]);

// Short link creation
Log::channel('security')->info('Short link created', [
    'user_id' => auth()->id(),
    'short_id' => $short->id,
    'ip' => request()->ip(),
]);

// Short link deletion
Log::channel('security')->info('Short link deleted', [
    'user_id' => auth()->id(),
    'short_id' => $short->id,
]);

// Failed authentication
Log::warning('Invalid OAuth provider attempted', [
    'provider' => $provider,
    'ip' => request()->ip(),
]);
```

### Access Logs

Monitor these locations for security events:

- **Main Log**: `storage/logs/laravel.log`
- **Security Log**: `storage/logs/security.log` (daily rotation)

---

## CI/CD Security

### GitHub Actions Workflows

The application includes automated security scanning workflows:

**File**: `.github/workflows/security.yml`

### Workflow Jobs

1. **Dependency Check**
   - Runs `composer audit --locked`
   - Checks for known vulnerabilities in dependencies
   - Fails if vulnerabilities are found

2. **Secrets Scanning**
   - Uses TruffleHog to scan for exposed secrets
   - Checks for API keys, tokens, and credentials
   - Runs on every push

3. **CodeQL Analysis**
   - GitHub's static analysis engine
   - Detects security issues and code quality problems
   - Provides detailed reports

4. **Static Analysis**
   - Runs PHPStan for PHP static analysis
   - Checks for type errors and potential bugs
   - Validates code quality

### Running Workflows Locally

To run security checks locally:

```bash
# Check dependencies
composer audit --locked

# Run PHPStan (if installed)
vendor/bin/phpstan analyse app --level=max
```

---

## Best Practices

### Development

1. **Never Commit Secrets**
   - Use `.env.example` with placeholder values
   - Store real credentials in `.env` (excluded by `.gitignore`)
   - Use GitHub Secrets for CI/CD credentials

2. **Always Escape Output**
   - Use `{{ }}` in Blade templates by default
   - Only use `{!! !!}` for trusted HTML
   - Never output user input with `{!! !!}`

3. **Validate All Input**
   - Use Form Requests for validation
   - Implement custom validators when needed
   - Check authorization before processing requests

4. **Use HTTPS**
   - Enable HTTPS in production
   - Use HSTS headers (configured by middleware)
   - Force HTTPS in `.env.production`

5. **Keep Dependencies Updated**
   - Run `composer update` regularly
   - Check `composer audit` before deployment
   - Review security advisories

### Production Deployment

1. **Configuration**
   - Copy `.env.production` to `.env`
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Generate strong `APP_KEY`: `php artisan key:generate`

2. **Database**
   - Use strong passwords
   - Restrict database user permissions
   - Enable SSL for database connections
   - Regular backups

3. **Monitoring**
   - Monitor `storage/logs/` directory
   - Set up log rotation and archiving
   - Configure alerts for security events
   - Monitor rate limit violations

4. **Updates**
   - Subscribe to security advisories
   - Apply patches promptly
   - Test updates in staging first
   - Keep framework and dependencies current

---

## Resources

- [Laravel Security Documentation](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)
- [MDN Web Security](https://developer.mozilla.org/en-US/docs/Web/Security)

---

## Support

For security issues, please report them to the project maintainers privately. Do not open public issues for security vulnerabilities.
