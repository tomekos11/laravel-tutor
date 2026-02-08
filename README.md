cd frontend
npm install 
cd ..
cd backend
composer install
copy .env.example .env //jak nie masz env
php artisan migrate
php artisan passport:install


php artisan serve / quasar dev
