#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
    php artisan key:generate --force --no-interaction
fi

echo "Waiting for database..."
until php -r "
    try {
        new PDO(
            sprintf('mysql:host=%s;port=%s;dbname=%s', getenv('DB_HOST'), getenv('DB_PORT') ?: '3306', getenv('DB_DATABASE')),
            getenv('DB_USERNAME'),
            getenv('DB_PASSWORD')
        );
        exit(0);
    } catch (Throwable \$e) {
        exit(1);
    }
" 2>/dev/null; do
    sleep 2
done

php artisan migrate --force --no-interaction
php artisan storage:link --force --no-interaction 2>/dev/null || true

if [ "$RUN_SEED" = "true" ]; then
    php artisan db:seed --force --no-interaction
fi

exec apache2-foreground
