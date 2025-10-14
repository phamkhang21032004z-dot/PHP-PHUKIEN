#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

if [ "$EUID" -ne 0 ]
  then echo "Please run as root"
fi

if [ "$env" != "local" ]; then
    echo "Caching configuration..."
    (cd /var/www && php artisan config:cache && php artisan route:cache && php artisan view:cache )
    # && supervisorctl reread && supervisorctl update && supervisorctl start laravel-worker:*
    exec php-fpm
fi

if [ "$role" = "app" ]; then

    exec php-fpm

elif [ "$role" = "supervisor" ]; then

    echo "supervisor start..."
    # (/usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf &)

elif [ "$role" = "queue" ]; then

    echo "Queue role"
    # (cd /var/www && php artisan queue:work --tries=3)
    # echo "Queue ready"
    # exit 1

elif [ "$role" = "scheduler" ]; then

    echo "Scheduler role"
    (cd /var/www && php artisan schedule:run)
    echo "Scheduler ready"
    # exit 1

else
    echo "Could not match the container role \"$role\""
    exit 1
fi

if [ "$role" = "app" ]; then

    exec php-fpm

elif [ "$role" = "queue" ]; then

    echo "Queue role"
    exit 1

elif [ "$role" = "scheduler" ]; then

    while [ true ]
    do
      php /var/www/artisan schedule:run --verbose --no-interaction &
      sleep 60
    done

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
