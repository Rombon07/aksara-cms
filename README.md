# KabarKini Digital Publishing CMS

KabarKini is a modern Digital Publishing Content Management System built with Laravel 11, Blade, and Tailwind CSS. It features a custom Role-Based Access Control (RBAC) system for Journalists and Editors, optimized image uploads, and a clean responsive public homepage.

## 🚀 Features

- **Custom RBAC:** Simple but secure `admin`, `editor`, and `journalist` roles.
- **Journalist Dashboard:** Create, edit, and submit articles for review.
- **Editor Dashboard:** Review queue to publish or send back articles with revision notes.
- **Optimized Thumbnails:** Automatic image resizing via Intervention Image.
- **Public Portal:** A beautiful, responsive single-page scrolling layout.

## 🛠 Prerequisites

To run this application, ensure you have:
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL 8.0+ (running locally via XAMPP, Herd, etc.)

## 📦 Setup Instructions

Follow these step-by-step instructions to set up the project locally:

### 1. Database Configuration
Ensure your local MySQL server is running. Create a new database named `db_publisher_media`.
```sql
CREATE DATABASE IF NOT EXISTS db_publisher_media;
```

### 2. Install Dependencies
Navigate into the project directory (`kabarkini`) and run:
```bash
composer install
npm install
```

### 3. Environment Setup
The `.env` file should already be configured to connect to `db_publisher_media` on `127.0.0.1`. If you have a specific database password, open `.env` and update `DB_PASSWORD=`.

### 4. Run Migrations & Seeders
This will create all tables and populate the database with default categories, a journalist, and an editor.
```bash
php artisan migrate:fresh --seed
```

### 5. Storage Link
Create the symbolic link to make your locally uploaded thumbnails accessible to the public.
```bash
php artisan storage:link
```

### 6. Build Assets & Start Server
Build the Tailwind CSS assets:
```bash
npm run build
```
*(Or `npm run dev` if you are actively making changes)*

Then start the Laravel development server:
```bash
php artisan serve
```

## 🧪 Testing the Application

1. **Public Portal:** Open `http://localhost:8000` in your browser.
2. **Journalist Access:**
   - Go to `http://localhost:8000/login`
   - Email: `jurnalis@kabarkini.com`
   - Password: `password`
   - *Test creating an article and submitting it for review.*
3. **Editor Access:**
   - Go to `http://localhost:8000/login`
   - Email: `editor@kabarkini.com`
   - Password: `password`
   - *Test reviewing the pending article, adding notes, or publishing.*
