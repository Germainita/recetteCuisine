sleep 10
php artisan migrate --seed 
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan jwt:secret --force
php artisan serve --host=0.0.0.0 --port=8000
apache2-foreground