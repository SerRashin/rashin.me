#!/bin/sh
# init.sh

cd /var/www/

composer install --prefer-source --no-interaction

echo db:5432:blog:dev:dev > ~/.pgpass
chmod 0600 ~/.pgpass

until psql -h "db" -U "dev" -d "blog"; do
  >&2 echo "Postgres is unavailable - sleeping"
  sleep 1
done

php bin/console doctrine:migrations:migrate --no-interaction
php bin/console cache:warmup --env=dev
php bin/console cache:warmup --env=prod

chown -R www-data:www-data ./var

php-fpm -F