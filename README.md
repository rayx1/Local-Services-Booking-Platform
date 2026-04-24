# Local Services Booking Platform

## Project Description
Local Services Booking Platform is a beginner-friendly full-stack web project built with Core PHP, MySQL, HTML, CSS, and JavaScript. It helps customers discover trusted local service providers, place bookings, and track their booking status. Service providers can list services and manage booking requests through their own dashboard, while the admin can oversee the entire platform.

## Problem Statement
- People struggle to find reliable local service providers quickly.
- Local service providers often do not have a proper digital platform to reach customers.
- Customers and providers need a simple way to track booking status.
- Traditional booking methods such as word of mouth or paper-based records are slow and unreliable.

## Proposed Solution
- Customers can register, log in, browse services by category, and book a service.
- Service providers can register, log in, create service listings, and manage bookings.
- Admin can manage users, categories, services, and bookings.
- Booking statuses are visible throughout the workflow: Pending, Accepted, Rejected, Completed.

## Project Goals
- Build a complete CRUD-based college project using Core PHP only.
- Demonstrate authentication, role-based access control, and secure password handling.
- Provide separate dashboards for customer, provider, and admin roles.
- Keep the project simple enough to understand, customize, and present.

## Technology Stack
| Layer | Technology |
|-------|------------|
| Frontend | HTML, CSS, JavaScript |
| Backend | Core PHP |
| Database | MySQL |
| Server | Apache (XAMPP / WAMP / LAMP compatible) |

## Features By Role
### Customer
- Register and log in
- Browse all active services
- Filter services by category
- View full service details
- Book services with date, time, address, and message
- Track own bookings and booking statuses

### Provider
- Register and log in
- Add new services
- Edit existing services
- View all own service listings
- View booking requests from customers
- Accept, reject, or mark bookings as completed

### Admin
- Log in to admin dashboard
- View platform statistics
- Manage users
- Manage categories
- View and update service statuses
- View all bookings

## Folder Structure
```text
local-services-booking-platform/
├── README.md
├── LICENSE
├── .gitignore
├── database.sql
├── index.php
├── about.php
├── contact.php
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   └── images/
├── config/
│   └── database.php
├── includes/
│   ├── auth.php
│   ├── footer.php
│   ├── functions.php
│   └── header.php
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── register.php
├── customer/
│   ├── book-service.php
│   ├── dashboard.php
│   ├── my-bookings.php
│   ├── service-details.php
│   └── services.php
├── provider/
│   ├── add-service.php
│   ├── booking-requests.php
│   ├── dashboard.php
│   ├── edit-service.php
│   └── my-services.php
└── admin/
    ├── bookings.php
    ├── categories.php
    ├── dashboard.php
    ├── services.php
    └── users.php
```

## Database Design
### `users`
- `id`
- `name`
- `email`
- `password`
- `phone`
- `address`
- `role` ENUM(`customer`, `provider`, `admin`)
- `created_at`

### `categories`
- `id`
- `name`
- `description`
- `created_at`

### `services`
- `id`
- `provider_id`
- `category_id`
- `title`
- `description`
- `price`
- `location`
- `status` ENUM(`active`, `inactive`)
- `created_at`

### `bookings`
- `id`
- `customer_id`
- `service_id`
- `provider_id`
- `booking_date`
- `booking_time`
- `address`
- `message`
- `status` ENUM(`pending`, `accepted`, `rejected`, `completed`)
- `created_at`

### `contact_messages`
- `id`
- `name`
- `email`
- `subject`
- `message`
- `created_at`

## Installation Steps
1. Install XAMPP, WAMP, or another PHP + MySQL local server package.
2. Clone or copy this project into your web server root folder such as `htdocs`.
3. Create a MySQL database named `local_services_booking_platform` if needed.
4. Import [`database.sql`](/F:/GOOGLE%20Antigravity/BCA/local-services-booking-platform/database.sql) into MySQL using phpMyAdmin or the MySQL CLI.
5. Open [`config/database.php`](/F:/GOOGLE%20Antigravity/BCA/local-services-booking-platform/config/database.php) and update database credentials if your local setup uses different values.
6. Start Apache and MySQL.
7. Open the project in the browser:
   `http://localhost/local-services-booking-platform/`

## Default Login Credentials
| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@localservices.test` | `admin123` |
| Customer | `amit.customer@localservices.test` | `customer123` |
| Provider | `rakesh.provider@localservices.test` | `provider123` |

## Screens / Pages Included
- Home page
- About page
- Contact page
- Login page
- Registration page
- Customer dashboard
- Browse services page
- Service details page
- Book service page
- My bookings page
- Provider dashboard
- Add service page
- My services page
- Edit service page
- Booking requests page
- Admin dashboard
- Manage users page
- Manage categories page
- Manage services page
- Manage bookings page

## Security Features
- Password hashing with `password_hash()`
- Password verification with `password_verify()`
- Prepared statements using `mysqli`
- Session-based authentication
- Role-based access control
- Output escaping with `htmlspecialchars()`
- Basic server-side and JavaScript form validation
- No plain-text passwords stored in the database

## Future Improvements
- Online payment integration
- Ratings and reviews
- Email and SMS notifications
- Google Maps integration
- Provider verification and approval flow
- Search by location and distance

## License
This project is licensed under the MIT License. See the [`LICENSE`](/F:/GOOGLE%20Antigravity/BCA/local-services-booking-platform/LICENSE) file for details.
