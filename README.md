# Aksara Digital Publishing CMS

Aksara is a modern Digital Publishing Content Management System built with Laravel 11, Blade, and Tailwind CSS. It features a custom Role-Based Access Control (RBAC) system for Readers, Authors, and Editors, optimized media uploads, interactive user features, and a clean responsive public homepage.

## 🚀 Features

- **Custom RBAC:** Simple and secure roles: `editor`, `author`, and `reader`.
- **Author Dashboard:** Create, edit, and submit articles or e-books for review. E-books support PDF file uploads.
- **Editor Dashboard:** Review queue to publish pending submissions or send them back to the author with revision notes.
- **Optimized Media & Serving:** 
  - Automatic image resizing via Intervention Image (GD Driver).
  - Custom media serving route to bypass symlink issues on local development environments (e.g., Windows/XAMPP).
- **Interactive Reader Actions:** Logged-in readers can Like, Bookmark, and Comment on articles dynamically (handled with AJAX/JSON).
- **Personal Reading Library:** A page for readers to manage and access their bookmarked articles.
- **Search & Category Filtering:** Seamless search capability and category navigation.

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
Navigate into the project directory (`aksara`) and run:
```bash
composer install
npm install
```

### 3. Environment Setup
The `.env` file should be configured to connect to `db_publisher_media` on `127.0.0.1`. If you have a specific database password, open `.env` and update `DB_PASSWORD=`.

### 4. Run Migrations & Seeders
This will create all tables and populate the database with default categories, dummy articles, and test accounts.
```bash
php artisan migrate:fresh --seed
```

### 5. Storage Link
Create the symbolic link to make your locally uploaded files accessible (optional as Aksara also features a fallback media controller).
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
2. **Author Access:**
   - Go to `http://localhost:8000/login`
   - Email: `author@aksara.com`
   - Password: `password`
   - *Test creating an article or e-book and submitting it for review.*
3. **Editor Access:**
   - Go to `http://localhost:8000/login`
   - Email: `editor@aksara.com`
   - Password: `password`
   - *Test reviewing the pending article, adding notes, or publishing.*
4. **Reader Access:**
   - Go to `http://localhost:8000/login`
   - Email: `reader@aksara.com`
   - Password: `password`
   - *Test bookmarking, liking, and leaving comments on published articles.*
