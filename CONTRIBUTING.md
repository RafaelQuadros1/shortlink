# Contributing to ShortLink

Thank you for your interest in contributing to ShortLink! We welcome contributions from the community to help improve the project. Please take a moment to review this document before submitting your contributions.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Workflow](#development-workflow)
- [Coding Standards](#coding-standards)
- [Testing](#testing)
- [Security](#security)
- [Submitting Changes](#submitting-changes)
- [Reporting Issues](#reporting-issues)

## Code of Conduct

We are committed to providing a welcoming and inclusive environment for all contributors. Please:

- Be respectful and professional
- Be inclusive and supportive
- Focus on what is best for the community
- Show empathy towards other community members

## Getting Started

### Prerequisites

- PHP 8.3 or higher
- Node.js 18+ and npm
- Composer
- Git

### Setup Development Environment

1. **Fork the Repository**
   ```bash
   # Visit https://github.com/RafaelQuadros1/shortlink and click "Fork"
   ```

2. **Clone Your Fork**
   ```bash
   git clone https://github.com/YOUR_USERNAME/shortlink.git
   cd shortlink
   ```

3. **Add Upstream Remote**
   ```bash
   git remote add upstream https://github.com/RafaelQuadros1/shortlink.git
   ```

4. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

5. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

## Development Workflow

### Creating a Feature Branch

Always create a new branch for your changes:

```bash
# Update main branch
git fetch upstream
git checkout main
git merge upstream/main

# Create feature branch
git checkout -b feature/your-feature-name
```

Branch naming convention:
- `feature/add-user-preferences` - New features
- `bugfix/fix-login-issue` - Bug fixes
- `chore/update-dependencies` - Dependencies or tooling
- `docs/improve-readme` - Documentation

### Making Changes

1. **Follow Coding Standards**
   - See [Coding Standards](#coding-standards) section below
   - Run `vendor/bin/pint` to format your code

2. **Write Tests**
   - Add tests for new features
   - Ensure all tests pass: `php artisan test --compact`
   - Aim for meaningful test coverage

3. **Update Documentation**
   - Update README.md if needed
   - Add PHPDoc comments to new methods
   - Update SECURITY.md if security-related

4. **Commit Messages**
   - Use clear, descriptive commit messages
   - Reference issues when applicable: `Fix #123`
   - Examples:
     ```
     Add feature to track link expiration
     Fix null pointer in authentication controller
     Update security documentation
     ```

### Before Pushing

```bash
# Format code
vendor/bin/pint --dirty

# Run tests
php artisan test --compact

# Check for security issues
composer audit

# Lint frontend code
npm run lint
```

## Coding Standards

### PHP Code Style

We follow Laravel Pint standards:

```bash
# Format modified files
vendor/bin/pint --dirty

# Format entire project
vendor/bin/pint
```

Key standards:
- Use explicit return types and type hints
- Use PHP 8 constructor property promotion
- Use descriptive variable/method names
- Add PHPDoc blocks for complex logic
- Always use curly braces for control structures

**Example:**
```php
<?php

namespace App\Http\Controllers;

use App\Models\Short;
use Illuminate\Http\Request;
use App\Services\InputValidator;

class ShortController extends Controller
{
    public function __construct(
        private InputValidator $validator
    ) {}

    public function store(Request $request): string
    {
        $validated = $this->validator->validateUrl($request->input('url'));
        $short = Short::create(['url' => $validated]);
        
        return $short->short_code;
    }
}
```

### Frontend Code

- Use Tailwind CSS for styling
- Follow existing component patterns
- Ensure responsive design
- Test on multiple screen sizes

## Testing

### Running Tests

```bash
# Run all tests
php artisan test --compact

# Run specific test file
php artisan test tests/Feature/ShortControllerTest.php --compact

# Run with filter
php artisan test --compact --filter=testStoreCreatesShortUrl
```

### Writing Tests

We use Pest for testing. Create tests with:

```bash
php artisan make:test YourFeatureTest --pest
```

**Example test:**
```php
<?php

use App\Models\Short;
use App\Models\User;

test('user can create a short link', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->post('/shorts', [
        'url' => 'https://example.com/very-long-url',
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('shorts', [
        'url' => 'https://example.com/very-long-url',
        'user_id' => $user->id,
    ]);
});
```

### Coverage

- Aim for meaningful test coverage
- Test both happy paths and edge cases
- Test authorization and validation

## Security

### Reporting Security Issues

**Do not** open public issues for security vulnerabilities. See [SECURITY_POLICY.md](SECURITY_POLICY.md) for details.

### Security Considerations

When contributing:
- Never commit secrets or API keys
- Validate and sanitize all user input
- Use parameterized queries (Eloquent ORM)
- Add rate limiting for sensitive endpoints
- Review [SECURITY.md](SECURITY.md) for current security measures
- Run `composer audit` to check for vulnerable dependencies

## Submitting Changes

### Pull Request Process

1. **Ensure Tests Pass**
   ```bash
   php artisan test --compact
   ```

2. **Format Code**
   ```bash
   vendor/bin/pint --dirty
   ```

3. **Push to Your Fork**
   ```bash
   git push origin feature/your-feature-name
   ```

4. **Open a Pull Request**
   - Go to GitHub and open a PR from your branch to `main`
   - Use a clear title and description
   - Reference related issues with `Fixes #123`
   - Ensure CI/CD checks pass

### PR Description Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Documentation update
- [ ] Security improvement

## Changes Made
- Change 1
- Change 2
- Change 3

## Testing
- [ ] Added tests for new functionality
- [ ] All tests pass locally
- [ ] Manual testing completed

## Security
- [ ] No secrets committed
- [ ] Input validation added if needed
- [ ] Security review completed if needed

## Checklist
- [ ] Code follows Laravel Pint standards
- [ ] Documentation updated if needed
- [ ] No breaking changes
```

### Review Process

- Automated checks must pass (tests, security scanning, code quality)
- At least one maintainer approval required
- Address feedback promptly
- Keep your branch updated with main

## Reporting Issues

### Bug Reports

Include:
- PHP and Laravel version
- Steps to reproduce
- Expected behavior
- Actual behavior
- Screenshots (if applicable)
- Error messages and stack traces

### Feature Requests

Include:
- Clear description of the feature
- Use cases and benefits
- Possible implementation approach
- Examples from other projects (if applicable)

## Questions?

- Open an issue with `[Question]` prefix
- Check existing issues first
- Join our community discussions

## License

By contributing to ShortLink, you agree that your contributions will be licensed under the MIT License.

---

Thank you for contributing to ShortLink! 🎉
