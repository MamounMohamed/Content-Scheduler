# Project Setup Guide

## Prerequisites
Before you start, ensure you have the following installed:
- **PHP** (version required by the project)
- **Composer** (latest version recommended)
- **Node.js** (latest LTS version)
- **NPM** (installed with Node.js)
- **SQLite** (or any other database of your choice)

## Installation Steps

### 1. Clone the Repository
```sh
git clone https://github.com/your-repo/project.git
cd project
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

### Seeding the Database (After Testing)
To seed the database, run the following command:
  ```sh
  php artisan db:seed
  ```
  users will be created with the following credentials with seeded data:
  - main_user@test.com (password: 123456)
  - test_user@test.com (password: 123456)

## Running the `posts:update-status` Command
To manually update the post statuses, run:
```sh
php artisan posts:update-status
```

## Running the `UpdatePostStatusJob` Job
To update the post statuses every minute, run:
```sh
php artisan schedule:run
```
don't forget to add the cron job to your server 


## Documentation

### Features Implemented
- **User Authentication**: Users can register, login, update their profile, and reset their password.
- **Post Management**: Users can create, schedule, edit, delete, and view unpublished posts.
- **Platform Activity Status**: Users can activate and deactivate the activity status of the platform.
- **Post Scheduling**: Scheduled posts are updated when their scheduled time passes using the `posts:update-status` command.
- **UpdatePostStatusJob**: A job is scheduled to update the post statuses every minute.
- **Scopes and Function on Models** : Used for reusable queries and functions on models.
- **Database Transactions**: Utilized database transactions to ensure data integrity.
- **Caching**: Used for retrieving lists and single items, resetting on updates, deletions, and creations to ensure data consistency.
- **Unit Testing**: API unit tests have been implemented.
- **React Reusable Components**: Created pages with reusable components:
  - `Posts\Index`, `Posts\View`, `Posts\Manage` (Edit/Create)
  - `Platform\Index`
  - `Errors\Unauthorized`, `Errors\NotFound`
- **Form Validation** : Ensured robust form validation throughout the application 
- **Pagination and Filtering** : Utilized pagination and filtering to enhance user experience
- **Error Handling** : Implemented error pages for 404 and 403 errors to provide clear and informative error messages

### Tech Stack
- **Backend**: Laravel with SQLite
- **Frontend**: React with Inertia.js
- **Caching**: Implemented for optimized performance
- **Database**: SQLite 

### Main Project Files
#### Models
- `Post`
- `Platform`
- `PostPlatform`
- `User`

#### Controllers
- `PostController`
- `PlatformController`
- `Auth\Controllers`
- `ProfileController`

#### Services
- `PostService`
- `PlatformService`

#### Requests
- `Http\Requests\PostRequest`

#### Resources
- `Http\Resources\PostResource`
- `Http\Resources\PlatformResource`

#### Tests
- `Tests\Features\PostTest`
- `Tests\Features\PlatformTest`
- `Tests\Features\AuthTest`
- `Tests\Features\ProfileTest`

## Additional Notes
- Ensure your `.env` file is correctly configured with the database and other necessary settings.
- The used database is `sqlite`, so you may need to install the `pdo_sqlite` extension for your PHP version if you are using a different database than SQLite then make sure to change the `DB_CONNECTION` in the `.env` file to your desired database.

