<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## Test Users

After running `make setup`, the following test users are available for API testing:

| Email | Password | Role | Permissions |
|-------|----------|------|-------------|
| `user@onfly.com` | `password` | Regular User | Create travel orders, view own orders |
| `admin@onfly.com` | `password` | Admin | All permissions (approve/cancel orders) |
| `test@example.com` | `password` | Regular User | Create travel orders, view own orders |

**Login Example:**
```bash
curl -X POST "http://localhost:8000/api/login" \
  -H "Content-Type: application/json" \
  -d '{"email": "user@onfly.com", "password": "password"}'
```

## API Documentation (Swagger/OpenAPI)

The API is documented using OpenAPI 3.0 spec via L5-Swagger. Access the documentation at:
- **URL**: http://localhost:8000/api/documentation
- **JSON Spec**: http://localhost:8000/docs?api-docs.json

### Endpoints

- **POST /api/login** - User authentication
- **POST /api/logout** - Revoke access token
- **GET /api/travel-orders** - List user's travel orders
- **POST /api/travel-orders** - Create travel order
- **GET /api/travel-orders/{id}** - Get specific travel order
- **PATCH /api/travel-orders/{id}/approve** - Approve travel order (admin only)
- **PATCH /api/travel-orders/{id}/cancel** - Cancel travel order (admin only)

### Maintaining Swagger Documentation

The OpenAPI specification is stored in `storage/api-docs/api-docs.json`. To update documentation:

1. Edit the JSON file to reflect any API changes
2. Update request/response schemas in the components section
3. Add/remove paths as needed

**Note**: The specification is served **statically** (not auto-generated) due to compatibility issues with swagger-php 3.5.1. The library encounters parsing errors when processing OpenAPI annotations (`@OA\PathItem() not found`), making automatic generation unreliable. This approach ensures:
- Consistent and reliable documentation
- No build-time failures
- Full control over the API spec
- Better performance (no runtime parsing)

If you need to regenerate from annotations in the future, consider upgrading to a newer version of the swagger-php library or using a different documentation tool.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
