# ShortLink - URL Shortening Application

A modern, secure URL shortening application built with Laravel 13, featuring social authentication and comprehensive security measures.

## Features

- 🔐 **Secure URL Shortening** - Create and manage short links with encryption
- 🔑 **Social Authentication** - GitHub and Google OAuth integration
- 📊 **Analytics** - Track clicks and referrer information
- ⚡ **Rate Limiting** - Built-in rate limiting to prevent abuse
- 🛡️ **Security Headers** - Comprehensive security headers (CSP, HSTS, etc.)
- 📝 **Audit Logging** - Security event logging and monitoring
- 🔒 **Encrypted Sessions** - Secure session management
- 🚀 **API Ready** - JSON API for programmatic access

## Security Features

This application implements industry-leading security practices:

✅ **XSS Prevention** - Output escaping and Content Security Policy  
✅ **CSRF Protection** - Token-based CSRF prevention  
✅ **SQL Injection Prevention** - Parameterized queries via Eloquent ORM  
✅ **Authentication Security** - Rate limiting and event logging  
✅ **Input Validation** - URL and short code validation  
✅ **OAuth Security** - Provider validation and error handling  
✅ **Rate Limiting** - Endpoint-specific rate limits  
✅ **Security Headers** - X-Frame-Options, X-Content-Type-Options, CSP, HSTS  
✅ **HTTPS Enforcement** - Automatic HTTPS in production  
✅ **Dependency Scanning** - Automated vulnerability detection  

For detailed security information, see [SECURITY.md](SECURITY.md).

## Requirements

- PHP 8.3 or higher
- Composer
- Node.js 18+ (for frontend assets)
- MySQL 5.7+ or SQLite

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/RafaelQuadros1/shortlink.git
cd shortlink
```

### 2. Install Dependencies

```bash
composer install
npm install --ignore-scripts
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup

```bash
php artisan migrate
```

### 5. Build Assets

```bash
npm run build
```

### 6. Start Development Server

```bash
composer run dev
```

The application will be available at `http://localhost:8000`.

## Configuration

### Environment Variables

See `.env.example` for all available options. Key security settings:

```env
APP_DEBUG=false              # Disable debug mode in production
APP_ENV=production           # Set environment
SESSION_ENCRYPT=true         # Encrypt session data
SESSION_SECURE_COOKIES=true  # HTTPS-only cookies
SESSION_HTTP_ONLY=true       # Prevent JavaScript access
```

### Social Authentication

Configure OAuth providers in `.env`:

```env
GITHUB_CLIENT_ID=your_client_id
GITHUB_CLIENT_SECRET=your_client_secret

GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
```

## Usage

### Create a Short Link

```bash
POST /shorts
Content-Type: application/json

{
  "url_origin": "https://example.com/very/long/url"
}
```

### View Short Link

```bash
GET /shorts
```

### Redirect to Original URL

```bash
GET /{short_code}
```

## Security Considerations

### For Deployment

1. **Set strong `.env` values**
   - `APP_KEY` - Run `php artisan key:generate`
   - Database credentials - Use strong passwords
   - OAuth secrets - Store securely

2. **Enable HTTPS**
   - Configure SSL/TLS certificates
   - Set `SESSION_SECURE_COOKIES=true`

3. **Monitor Logs**
   - Watch `storage/logs/security.log` for suspicious activity
   - Set up log rotation and archiving

4. **Keep Updated**
   - Regularly run `composer update`
   - Check `composer audit` before deployment
   - Monitor security advisories

### Reporting Security Issues

Please report security vulnerabilities privately. Do not create public issues for security problems.

See [SECURITY_POLICY.md](SECURITY_POLICY.md) for details.

## API Documentation

### Authentication

Most endpoints require authentication. Include the session cookie with requests.

### Endpoints

#### Create Short Link
```
POST /shorts
Authorization: Required
Content-Type: application/json

Request:
{
  "url_origin": "https://example.com/long/url"
}

Response:
{
  "short_url": "http://localhost:8000/abc123"
}
```

#### List Short Links
```
GET /shorts
Authorization: Required

Response:
[
  {
    "id": 1,
    "short_code": "abc123",
    "url_origin": "https://example.com/long/url",
    "clicks": 42
  }
]
```

#### View Short Link Details
```
GET /shorts/{id}
Authorization: Required

Response:
{
  "id": 1,
  "short_code": "abc123",
  "url_origin": "https://example.com/long/url",
  "clicks": 42,
  "click_details": [
    {
      "referrer": "https://twitter.com",
      "user_agent": "Mozilla/5.0..."
    }
  ]
}
```

#### Update Short Link
```
PUT /shorts/{id}
Authorization: Required
Content-Type: application/json

Request:
{
  "url_origin": "https://example.com/new/url"
}
```

#### Delete Short Link
```
DELETE /shorts/{id}
Authorization: Required
```

#### Redirect
```
GET /{short_code}

Response: 301 Redirect to original URL
```

## Testing

### Run Tests

```bash
php artisan test
```

### Security Testing

```bash
# Check for vulnerable dependencies
composer audit --locked

# Run static analysis
vendor/bin/phpstan analyse app --level=max
```

## Logging

### Log Files

- **Main Log**: `storage/logs/laravel.log` - Application events
- **Security Log**: `storage/logs/security.log` - Security-related events

### Logged Events

- User authentication
- Short link creation, update, deletion
- Failed authentication attempts
- Invalid requests
- Rate limit violations

## Development

### Project Structure

```
app/
├── Http/
│   ├── Controllers/      # Application controllers
│   ├── Middleware/       # HTTP middleware (including security headers)
│   └── Requests/         # Form requests with validation
├── Models/               # Eloquent models (User, Short, Click)
├── Policies/             # Authorization policies
├── Providers/            # Service providers (including security)
└── Services/             # Helper services (URL validation, etc.)

config/
├── app.php              # Application configuration
├── auth.php             # Authentication configuration
├── session.php          # Session configuration
└── logging.php          # Logging configuration

routes/
├── web.php              # Web routes with security middleware
└── console.php          # Console commands

resources/views/         # Blade templates with XSS protection
tests/                   # Test suite

.env.example             # Example environment variables
.env.production          # Production environment template
.github/workflows/       # CI/CD security workflows
```

### Key Files

- **SecurityHeadersMiddleware**: `app/Http/Middleware/SecurityHeadersMiddleware.php`
- **Input Validator**: `app/Services/InputValidator.php`
- **Security Provider**: `app/Providers/SecurityServiceProvider.php`
- **Security Configuration**: `config/logging.php`, `config/session.php`

## Performance

### Rate Limiting

- Short link creation: 30 requests/minute
- Link redirection: 300 requests/minute
- Social auth: 30 requests/minute per provider

### Caching

- Links are cached for fast retrieval
- Analytics data is aggregated for performance

## License

This project is open source and available under the [MIT license](LICENSE.md).

## Support

For support, please:

1. Check the [documentation](SECURITY.md)
2. Review [existing issues](https://github.com/RafaelQuadros1/shortlink/issues)
3. Create a new issue if needed (non-security)
4. Report security issues privately - see [SECURITY_POLICY.md](SECURITY_POLICY.md)

## Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

Please ensure:
- Code follows Laravel and PHP best practices
- Security best practices are maintained
- Tests pass (`php artisan test`)
- Code is formatted with Pint (`vendor/bin/pint`)

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history and updates.

## Security Credits

Thank you to the Laravel community and security researchers who help keep this application secure.

---

**Last Updated**: June 2024  
**Maintained by**: Rafael Quadros
