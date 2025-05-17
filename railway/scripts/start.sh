touch /app/database/database.sqlite
php artisan migrate:fresh --force --seed
php artisan serve --host=0.0.0.0 --port=8000
