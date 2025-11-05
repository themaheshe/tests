# Digital8 Job Interview Test - Laravel API

This repository contains a Laravel test project for the Digital8 job interview process. It demonstrates API development, user-level security, validation, logging, and integrations.

## Implemented Features

- **CRUD API for Client Records**: Users can create, read, update, and delete their own client records only.
- **User Ownership with Policies**: Authorization via Laravel Policy ensures users only access/modify their records.
- **Database Schema**:
  - `clients` table: fields include `user_id`, `first_name`, `last_name`, `email`, `age`, `linkedInUrl`.
  - `user_logs` table: logs all create, update, and delete actions with `action`, `user_id`, `date_created`.
- **FormRequest Validation**: All input for client records is validated (email, numeric/age|max:100, url, required fields).
- **Transactional Actions**: All changes to client records are performed in DB transactions with action audit logging.
- **Notifications**:
  - Email notification sent upon client creation (using Laravel's native system).
  - Slack notification also sent, via a service class (`SlackService`) implementing a `NotificationProvider` interface (uses dependency injection).
- **API Routing**: `/api/clients` endpoints, protected by `auth:sanctum` middleware.

---

## Usage & API Examples

### 1. Setup and Run
1. Install dependencies:
   ```bash
   composer install
   npm install && npm run build
   ```
2. Configure `.env` for database, mail, and Slack webhook as needed.
3. Run migrations:
   ```bash
   php artisan migrate
   ```
4. Run the dev server:
   ```bash
   php artisan serve
   ```

---

### 2. Authentication
All endpoints require authentication via Laravel Sanctum (add `Authorization: Bearer {token}` header).

---

### 3. API Endpoints

#### List Own Clients
```http
GET /api/clients
Authorization: Bearer {token}
```
**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@example.com",
      "age": 33,
      "linkedInUrl": "https://linkedin.com/in/johndoe"
    },
    ...
  ]
}
```

#### Show Client
```http
GET /api/clients/{id}
Authorization: Bearer {token}
```
**Response:**
```json
{
  "data": {
    "id": 2,
    "first_name": "Jane",
    "last_name": "Smith",
    "email": "jane@example.com",
    "age": 29,
    "linkedInUrl": "https://linkedin.com/in/janesmith"
  }
}
```

#### Create New Client
```http
POST /api/clients
Authorization: Bearer {token}
Content-Type: application/json
{
  "first_name": "Jane",
  "last_name": "Smith",
  "email": "jane@example.com",
  "age": 29,
  "linkedInUrl": "https://linkedin.com/in/janesmith"
}
```
**Response:** (201)
```json
{
  "id": 2,
  "first_name": "Jane",
  "last_name": "Smith",
  "email": "jane@example.com",
  "age": 29,
  "linkedInUrl": "https://linkedin.com/in/janesmith"
}
```
- Email + Slack notification sent!

#### Update Client
```http
PUT /api/clients/{id}
Authorization: Bearer {token}
Content-Type: application/json
{
  "first_name": "Janet",
  "age": 30
}
```
**Response:**
```json
{
  "data": {
    "id": 2,
    "first_name": "Janet",
    "last_name": "Smith",
    "email": "jane@example.com",
    "age": 30,
    "linkedInUrl": "https://linkedin.com/in/janesmith"
  }
}
```

#### Delete Client
```http
DELETE /api/clients/{id}
Authorization: Bearer {token}
```
**Response:**
```json
{
  "message": "Client deleted."
}
```

---

### 4. Validation Rules (applied on create & update)
- `first_name`/`last_name`: required, string
- `email`: required, valid email, unique
- `age`: required, integer, max 100
- `linkedInUrl`: required, valid URL

---

### 5. Notifications & Logging
- When a client is created:
  - An email is sent to the authenticated user
  - A Slack notification is sent using the SlackService
- All create, update, delete actions are logged to `user_logs`

---

The rest of this README contains standard Laravel documentation.

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

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
