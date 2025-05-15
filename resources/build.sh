npm #!/bin/bash
echo "Installing NPM dependencies..."
npm install
echo "Building assets..."
npm run build 
echo "installing composer dependencies..."
composer install
echo "Building assets..."
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
echo "Done!"
