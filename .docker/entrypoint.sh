if [ ! -f .env ]; then
    echo "APP_KEY=" >> .env
    php artisan key:generate
    php artisan optimize
fi

php artisan serve --host=0.0.0.0 --port=8080
