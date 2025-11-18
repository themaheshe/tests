# Demo For Job Interview Test - Laravel API

This repository contains a Laravel test project for demo purpose. It demonstrates API development, user-level security, validation, logging, PHPunit test and integrations.

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