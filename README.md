# ACLC Clinic Information and Medical Inventory Management System

A browser-based clinic management system developed for **ACLC College of Mandaue**, Mandaue City, Cebu. The system replaces manual logbook-based clinic procedures with a centralized, digital solution for managing student medication requests, inventory monitoring, and report generation.

---

## About the System

The system automates the following clinic operations:

- **Student Medication Requests** — record, approve, reject, and disburse medications with automatic stock deduction
- **Inventory Management** — track medication stock levels with low-stock and expiration alerts
- **Categories** — organize medications by category
- **Reports** — generate downloadable PDF reports for restock needs and student visit logs
- **Staff Management** — admin can create staff accounts and enable/disable module access per staff
- **Module Access Control** — admin can toggle access to Categories, Medications, Requests, and Reports for clinic staff
- **Archive** — soft-delete medications and restore or permanently remove them from the archive

---

## User Roles

| Role | Access |
|------|--------|
| **Admin** | Full access to all modules — manages inventory, categories, staff accounts, medication requests, and generates reports |
| **Clinic Staff** | Access to assigned modules only — records and processes student medication requests, monitors stock, approves or disburses medications, views reports |

---

## Tech Stack

| Technology | Purpose |
|------------|---------|
| Laravel 9 | PHP web framework — backend, routing, authentication, database management |
| PHP 8.0+ | Server-side scripting language |
| MySQL (XAMPP) | Relational database — stores all clinic and inventory data |
| Bootstrap 5.3 | Frontend CSS framework — responsive UI |
| Bootstrap Icons | Icon library used throughout the UI |
| barryvdh/laravel-dompdf | PDF generation for restock and visit log reports |
| Composer | PHP dependency manager |

---

## Database Tables

- `users` — admin and clinic staff accounts
- `categories` — medication categories
- `medications` — inventory items with stock levels, expiry dates, and low-stock thresholds
- `medication_requests` — student medication requests with status tracking
- `medication_request_items` — individual medication items per request
- `disbursements` — records of dispensed medications
- `module_settings` — controls which modules are active for staff

---

## Requirements

- PHP 8.0+
- Composer
- XAMPP (Apache + MySQL)
- Web browser (Google Chrome recommended)

---

## Installation

1. **Clone or copy the project** into your XAMPP `htdocs` folder.

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Copy the environment file and configure it:**
   ```bash
   cp .env.example .env
   ```
   Update `.env` with your database credentials:
   ```
   DB_DATABASE=your_database_name
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate the application key:**
   ```bash
   php artisan key:generate
   ```

5. **Run migrations and seed the database:**
   ```bash
   php artisan migrate --seed
   ```
   This creates all tables and seeds the default admin account.

6. **Start XAMPP** — make sure Apache and MySQL modules are running.

7. **Access the system** in your browser:
   ```
   http://localhost/your-project-folder/public
   ```
   Or if using `php artisan serve`:
   ```
   http://localhost:8000
   ```

---


> Change the password immediately after first login via **Change Password** in the sidebar.

---

## Networking (Multi-Computer Setup)

The system supports a client-server setup within a local network:

- **Server** — the computer running XAMPP with the database (clinic office / admin PC)
- **Client** — other computers (clinic staff workstations) accessing the system via browser
- **Connection** — UTP Cat6 cables with TCP/IP configuration
- Clients access the system by pointing their browser to the server's local IP address:
  ```
  http://<server-ip>/your-project-folder/public
  ```

> This is an **offline, browser-based system** — no internet connection is required.

---

## Key Features

- ✅ Real-time low-stock and expiration alerts on the dashboard
- ✅ Full medication request workflow: Pending → Approved → Disbursed / Rejected
- ✅ Automatic stock deduction on disbursement
- ✅ Soft-delete archive for medications (restore or permanently delete)
- ✅ PDF download for restock reports and student visit logs
- ✅ Module toggle — admin can enable/disable modules for staff
- ✅ Staff account activation/deactivation
- ✅ Questionnaire available at `/questionnaire` for system evaluation



## License

Developed for academic purposes — ACLC College of Mandaue, Mandaue City, Cebu.
