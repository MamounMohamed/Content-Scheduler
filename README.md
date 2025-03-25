# Project Setup Guide

## Prerequisites
Before you start, ensure you have the following installed:
- **PHP** (version required by the project)
- **Composer** (latest version recommended)
- **Node.js** (latest LTS version)
- **NPM** (installed with Node.js)
- **SQLite** 

## Installation Steps

### 1. Clone the Repository
```sh
git clone https://github.com/MamounMohamed/Content-Scheduler.git
cd Content-Scheduler
```

### 2. Install PHP Dependencies
```sh
composer install
```

### 3. Install JavaScript Dependencies
```sh
npm install
```

### 4. Build Frontend Assets
```sh
npm run build
```

### 5. Set Up Environment Variables
```sh
cp .env.example .env
```

### 6. Generate Application Key
```sh
php artisan key:generate
```

### 7. Run Database Migrations
```sh
php artisan migrate --seed
```

### 8. Link Storage Directory
```sh
php artisan storage:link
```

### 9. Start the Development Server
```sh
php artisan serve
```

## Testing the Project
To test the project, you can run:
```sh
php artisan test
```

## Seeding the Database
To seed the database, run:
```sh
php artisan db:seed
```

## Running the `posts:update-status` Command
To manually update the post statuses, run:
```sh
php artisan posts:update-status
```

## Additional Notes
- Ensure your `.env` file is correctly configured with the database and other necessary settings.
- The used database is `sqlite`, so you may need to install the `pdo_sqlite` extension for your PHP version.


