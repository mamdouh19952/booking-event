    # 🎟️ Booking — Event & Seat Booking System

A full-featured **Event & Seat Booking** REST API built with **Laravel**, supporting event browsing, seat reservation, category management, media uploads, role-based access control, and secure authentication via **Laravel Sanctum**.

---

## 📌 Table of Contents

- [About](#about)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Environment Setup](#environment-setup)
- [Database](#database)
- [API Endpoints](#api-endpoints)
- [Media Library](#media-library)
- [Project Structure](#project-structure)
- [Postman Collection](#postman-collection)
- [Security](#security)
- [License](#license)
- [Author](#author)

---

## 📖 About

**Booking** is a Laravel-based REST API for event & seat booking. Admins can manage events, categories, and bookings with full CRUD operations. Users can browse events, create bookings, and view their reservation history. The system uses **Eager Loading** for optimized queries, **Form Requests** for validation, **API Resources** for clean JSON responses, **Spatie MediaLibrary** for media management, and **Role Middleware** for access control.

---

## ✨ Features

### 👤 Authentication (Laravel Sanctum)
- Register
- Login / Logout
- Token-based authentication
- Role-based access (`admin` / `user`)

### 🪑 Booking (User)
- View all own bookings
- View booking details
- Create a booking for an event
- Delete a booking
- View booking with full event data

### 🗂️ Admin — Category Management
- View all categories
- View category with all its events
- Create / Update / Delete category

### 🎪 Admin — Event Management
- View all events with categories
- Create / Update / Delete event
- Manage event media via MediaLibrary

### 📋 Admin — Booking Management
- View all bookings with data
- View booking with full details
- Update booking status

### ⚡ Performance
- **Eager Loading** to eliminate N+1 query problems
- Optimized DB queries across all relations

### 🧹 Code Quality
- **Form Requests** — `BookingRequest`, `CreateEventRequest`, `LoginRequest`, `UpdateEventRequest`
- **API Resources** — `BookingResource`, `CategoryResource`, `EventResource`, `UserResource`
- **Services Layer** — `MediaServices` for media business logic
- **Role Middleware** for access control
- Clean MVC architecture

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12 |
| Language | PHP 8.2+ |
| Database | MySQL |
| Auth | Laravel Sanctum |
| Media | Spatie Laravel MediaLibrary |
| API | Laravel API Resources |
| Validation | Form Requests |

---

## 📋 Requirements

- PHP >= 8.2
- Composer
- MySQL
- Laravel 12

---

## 🚀 Installation

```bash
# 1. Clone the repository
git clone https://github.com/mamdouh19954/booking.git
cd booking

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Run migrations
php artisan migrate --seed

# 6. Link storage
php artisan storage:link

# 7. Serve the application
php artisan serve
```

---

## ⚙️ Environment Setup

Copy `.env.example` to `.env` and configure:

```env
APP_NAME=Booking
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=booking
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@gmail.com
MAIL_FROM_NAME="Booking"

# Geolocation
GEOLOCATION_API_KEY=your_api_key_here

# SMS (Vonage)
VONAGE_KEY=your_key
VONAGE_SECRET=your_secret
VONAGE_FROM="Booking"
```

> ⚠️ **Never commit your `.env` file. It is already in `.gitignore`.**

---

## 🗃️ Database

```bash
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed
```

### Main Tables

| Table | Description |
|-------|-------------|
| `users` | Registered users with roles |
| `categories` | Event categories |
| `events` | Events with details |
| `bookings` | User seat reservations |
| `media` | MediaLibrary managed files |

---

## 📡 API Endpoints

### 🔓 Public Routes (No Auth)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/register` | Register new user |
| POST | `/login` | Login & get token |
| GET | `/category.all` | Get all categories |
| GET | `/category.show/{id}` | Get category details |
| GET | `/event.all` | Get all events |
| GET | `/event.show/{id}` | Get event details |

### 🔐 Admin Routes (`auth:sanctum` + `role:admin`)

#### Category
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/category.create` | Create category |
| PUT | `/category.update/{id}` | Update category |
| DELETE | `/category.destroy/{id}` | Delete category |
| GET | `/category.allevents` | All categories with events |
| GET | `/category.show.w.events/{id}` | Category with its events |

#### Event
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/event.create` | Create event |
| PUT | `/event.update/{id}` | Update event |
| DELETE | `/event.destroy/{id}` | Delete event |
| GET | `/event.allcategories` | Events with categories |
| GET | `/event.show.w.categories/{id}` | Event with categories |

#### Booking (Admin)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/booking.alldata` | All bookings with data |
| GET | `/booking.with.data.show/{id}` | Booking with full details |
| PUT | `/booking.update/{id}` | Update booking status |

### 👤 User Routes (`auth:sanctum` + `role:user`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/logout` | Logout |
| GET | `/booking.all` | My bookings |
| GET | `/booking.show/{id}` | Booking details |
| POST | `/booking.create/{eventId}` | Create booking |
| DELETE | `/booking.destroy/{id}` | Cancel booking |
| GET | `/booking.with.data.show` | Booking with full event data |

---

## 🖼️ Media Library

Uses [Spatie Laravel MediaLibrary](https://spatie.be/docs/laravel-medialibrary) for event images via `MediaServices.php`.

```bash
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"
php artisan migrate
```

---

## 📁 Project Structure

```
booking/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php
│   │   │   ├── Booking/
│   │   │   │   └── BookingController.php
│   │   │   ├── Category/
│   │   │   │   └── CategoryController.php
│   │   │   └── Event/
│   │   │       └── EventController.php
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   ├── Requests/
│   │   │   ├── BookingRequest.php
│   │   │   ├── CreateEventRequest.php
│   │   │   ├── LoginRequest.php
│   │   │   └── UpdateEventRequest.php
│   │   └── Resources/
│   │       ├── BookingResource.php
│   │       ├── CategoryResource.php
│   │       ├── EventResource.php
│   │       └── UserResource.php
│   ├── Models/
│   │   ├── Booking.php
│   │   ├── Category.php
│   │   ├── Event.php
│   │   └── User.php
│   └── Services/
│       └── MediaServices.php
├── routes/
│   └── api.php
├── postman/
│   └── booking_api.postman_collection.json
├── .env.example
├── .gitignore
└── README.md
```

---

## 🧪 Postman Collection

Collection موجودة في فولدر `postman/` وتحتوي على:

- **Auth** — Register, Login, Logout
- **Category** — كل عمليات الـ Category (Admin)
- **Event** — كل عمليات الـ Event (Admin)
- **Booking** — Admin & User booking endpoints

```bash
1. افتح Postman
2. Import ملف postman/booking_api.postman_collection.json
3. اعمل Environment جديد وحط BASE_URL و TOKEN
```

---

## 🔒 Security

- `.env` excluded from version control
- All secrets loaded via `env()` helper
- Laravel Sanctum for API token authentication
- Role Middleware (`role:admin` / `role:user`) for access control
- Form Requests for input validation & sanitization
- Passwords stored using `bcrypt`

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---

## 🙋‍♂️ Author

Made with ❤️ by **Mamdouh**  
GitHub: [@mamdouh19954](https://github.com/mamdouh19954)
