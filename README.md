# ShortLink - URL Shortener with OAuth Authentication

<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo"></a>
</p>

<p align="center">
A modern, secure URL shortener built with Laravel 13, Tailwind CSS, and OAuth authentication.
</p>

<p align="center">
<a href="https://github.com/RafaelQuadros1/shortlink/actions"><img src="https://github.com/RafaelQuadros1/shortlink/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg" alt="License"></a>
</p>

## About ShortLink

ShortLink is a secure, feature-rich URL shortener application that enables users to:

- Create shortened URLs for long web addresses
- Manage their shortened links through an intuitive dashboard
- Authenticate securely using OAuth (GitHub, Google, etc.)
- Track link redirects with click analytics
- Access comprehensive privacy and security features

Built with Laravel 13 and modern web technologies, ShortLink prioritizes **security**, **performance**, and **user experience**.

## ✨ Key Features

- **OAuth Authentication** - Secure login with multiple OAuth providers (GitHub, Google)
- **URL Management** - Create, view, edit, and delete shortened links
- **Click Tracking** - Monitor redirect activity for each shortened URL
- **Security Headers** - Comprehensive security headers (CSP, HSTS, X-Frame-Options)
- **Rate Limiting** - Protection against abuse with intelligent rate limiting
- **Input Validation** - Robust URL and input validation
- **Privacy Policies** - Dedicated privacy, cookies, and terms of use pages
- **Security Logging** - Detailed security event logging
- **Modern UI** - Clean, responsive interface built with Tailwind CSS
- **AI Development Ready** - Built with Laravel Boost for agent-friendly development

## 🛡️ Security

ShortLink implements comprehensive security measures including:

- Encrypted sessions with HTTP-only cookies
- SQL injection prevention via Eloquent ORM
- XSS protection with input validation
- CSRF token protection
- Rate limiting on authentication and redirects
- OAuth provider validation
- Security headers middleware
- GitHub Actions security scanning (CodeQL, composer audit, secrets scanning)

For detailed security information, see [SECURITY.md](SECURITY.md) and [SECURITY_POLICY.md](SECURITY_POLICY.md).

## 📋 Requirements

- PHP 8.3 or higher
- Node.js 18+ (for frontend assets)
- npm or yarn
- Composer
- SQLite or MySQL (configured in `.env`)

## 🚀 Quick Start

### 1. Clone the Repository

```bash
git clone https://github.com/RafaelQuadros1/shortlink.git
cd shortlink
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup

```bash
php artisan migrate
```

### 5. Configure OAuth (GitHub, Google, etc.)

Update your `.env` file with OAuth credentials:

```env
GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret

GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
```

### 6. Build Assets and Start Development

```bash
npm run build
composer run dev
```

The application will be available at `http://localhost:8000`

## 📚 Available Commands

### Setup
```bash
composer run setup
```
Installs dependencies, generates app key, runs migrations, and builds frontend assets.

### Development
```bash
composer run dev
```
Starts Laravel development server, queue listener, and Vite for frontend bundling (with concurrent execution).

### Testing
```bash
composer run test
```
Runs the full test suite using Pest (PHP testing framework).

Filter specific tests:
```bash
php artisan test --compact --filter=testName
```

### Code Formatting
```bash
vendor/bin/pint --dirty
```
Formats PHP code according to Laravel Pint standards.

## 📁 Project Structure

```
shortlink/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Request handlers
│   │   ├── Middleware/      # HTTP middleware
│   │   └── Requests/        # Form request validation
│   ├── Models/              # Eloquent models (User, Short, Click)
│   ├── Policies/            # Authorization policies
│   ├── Services/            # Business logic (InputValidator, etc.)
│   └── Jobs/                # Queued jobs
├── database/
│   ├── migrations/          # Database schemas
│   ├── factories/           # Model factories for testing
│   └── seeders/             # Database seeders
├── routes/                  # Route definitions
├── resources/
│   ├── views/              # Blade templates
│   └── css/                # Tailwind CSS styles
├── tests/
│   ├── Feature/            # Feature tests
│   └── Unit/               # Unit tests
├── config/                 # Configuration files
└── public/                 # Static assets
```

## 🔐 Environment Configuration

### Development (.env.example)
```env
APP_ENV=local
APP_DEBUG=true
DATABASE_URL=sqlite:./database/database.sqlite
```

### Production (.env.production)
```env
APP_ENV=production
APP_DEBUG=false
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIES=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

For production deployment details, see [DEPLOYMENT_SECURITY_CHECKLIST.md](DEPLOYMENT_SECURITY_CHECKLIST.md).

## 🧪 Testing

ShortLink uses Pest for testing, providing an expressive and enjoyable testing experience.

```bash
# Run all tests
php artisan test --compact

# Run specific test file
php artisan test tests/Feature/ShortLinkTest.php --compact

# Run tests matching a pattern
php artisan test --compact --filter=redirect
```

Test coverage includes:
- URL shortening functionality
- OAuth authentication flows
- Authorization policies
- Input validation
- Security headers

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Commit your changes: `git commit -am 'Add your feature'`
4. Push to the branch: `git push origin feature/your-feature`
5. Submit a pull request

Please ensure:
- Code follows Laravel Pint standards (`vendor/bin/pint`)
- All tests pass (`php artisan test`)
- Security checks pass (CodeQL, secrets scanning)

## 📝 Code Style

This project uses **Laravel Pint** for code formatting. Before committing, run:

```bash
vendor/bin/pint --dirty
```

## 🔒 Security

If you discover a security vulnerability, please email [security@shortlink.dev](mailto:security@shortlink.dev) instead of using the issue tracker. See [SECURITY_POLICY.md](SECURITY_POLICY.md) for details.

## 📄 License

ShortLink is open-sourced software licensed under the [MIT License](LICENSE).

## 🔗 Useful Links

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com)
- [Laravel Socialite](https://laravel.com/docs/socialite)
- [Pest Testing](https://pestphp.com)
- [Security Guidelines](SECURITY.md)
- [Deployment Checklist](DEPLOYMENT_SECURITY_CHECKLIST.md)

## 💡 Development Notes

- This project uses **Laravel Boost** for AI-powered development assistance
- Frontend is built with **Tailwind CSS v4**
- Testing framework is **Pest v4**
- PHP version requirement: **8.3+**

---

**Made with ❤️ by Rafael Quadros**
