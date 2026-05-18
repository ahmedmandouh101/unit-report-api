# Unit Report API

A REST API built with Laravel that allows guests to report problems with their booked units, and admins to manage those reports.

---

## Features

- Guests can submit a report for a unit they booked
- One report per booking (duplicates are rejected)
- Guests can list and filter their own reports
- Admins can update report status with enforced transitions
- Authentication via Laravel Sanctum
- Fully paginated responses

---

## Tech Stack

- **PHP 8.2+**
- **Laravel 13**
- **MySQL**
- **Laravel Sanctum** (authentication)

---

## Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/ahmedmandouh101/unit-report-api.git
cd unit-report-api
```

### 2. Install dependencies

```bash
composer install
```

### 3. Set up environment

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials:

```env
DB_DATABASE=unit_report_api
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run migrations

```bash
php artisan migrate
```

### 5. Serve the application

```bash
php artisan serve
```

---

## API Endpoints

### Authentication

All endpoints require a Bearer token in the `Authorization` header:

```
Authorization: Bearer {your_token}
```

---

### Submit a report

```
POST /api/reports
```

**Request body:**

```json
{
    "unit_id": 1,
    "booking_id": 3,
    "type": "maintenance",
    "description": "The AC is not working properly."
}
```

**Validation rules:**

| Field | Rules |
|---|---|
| `unit_id` | required, exists in units table |
| `booking_id` | required, exists in bookings table |
| `type` | required, one of: `cleanliness`, `maintenance`, `noise`, `other` |
| `description` | required, min 10 chars, max 1000 chars |

**Success response `201`:**

```json
{
    "message": "Report submitted successfully.",
    "data": {
        "id": 1,
        "type": "maintenance",
        "description": "The AC is not working properly.",
        "status": "pending",
        "unit_id": 1,
        "booking_id": 3,
        "created_at": "2025-01-01 12:00:00"
    }
}
```

**Error responses:**

| Status | Reason |
|---|---|
| `403` | Booking does not belong to the authenticated user |
| `409` | A report for this booking already exists |
| `422` | Validation failed |

---

### List my reports

```
GET /api/reports
GET /api/reports?type=noise
GET /api/reports?status=pending
GET /api/reports?type=maintenance&status=in_review
```

**Query parameters (all optional):**

| Parameter | Values |
|---|---|
| `type` | `cleanliness`, `maintenance`, `noise`, `other` |
| `status` | `pending`, `in_review`, `resolved` |

**Success response `200`:**

```json
{
    "data": [
        {
            "id": 1,
            "type": "maintenance",
            "description": "The AC is not working properly.",
            "status": "pending",
            "unit_id": 1,
            "booking_id": 3,
            "created_at": "2025-01-01 12:00:00"
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 3,
        "per_page": 10,
        "total": 25
    }
}
```

---

### Update report status *(Admin only)*

```
PATCH /api/admin/reports/{report}/status
```

**Request body:**

```json
{
    "status": "in_review"
}
```

**Allowed status transitions:**

```
pending → in_review → resolved
```

Skipping steps or going backward will return a `422` error.

**Success response `200`:**

```json
{
    "message": "Report status updated successfully.",
    "data": {
        "id": 1,
        "type": "maintenance",
        "description": "The AC is not working properly.",
        "status": "in_review",
        "unit_id": 1,
        "booking_id": 3,
        "created_at": "2025-01-01 12:00:00"
    }
}
```

**Error responses:**

| Status | Reason |
|---|---|
| `403` | User is not an admin |
| `422` | Invalid status transition |

---

## Database Schema

### `unit_reports` table

| Column | Type | Description |
|---|---|---|
| `id` | bigint | Primary key |
| `user_id` | foreignId | The guest who submitted the report |
| `unit_id` | foreignId | The unit being reported |
| `booking_id` | foreignId | The booking associated with the report |
| `type` | enum | `cleanliness`, `maintenance`, `noise`, `other` |
| `description` | text | Details of the problem |
| `status` | enum | `pending`, `in_review`, `resolved` |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/Api/
│   │   └── UnitReportController.php
│   ├── Requests/
│   │   └── StoreUnitReportRequest.php
│   └── Resources/
│       └── UnitReportResource.php
├── Models/
│   └── UnitReport.php
database/
└── migrations/
    └── create_unit_reports_table.php
routes/
└── api.php
```

---

## Author

**Ahmed Mandouh** — Backend Developer  
[GitHub](https://github.com/ahmedmandouh101) · [LinkedIn](https://www.linkedin.com/in/ahmedmandouh101)
