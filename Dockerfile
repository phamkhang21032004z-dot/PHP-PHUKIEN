FROM php:8.2-fpm
RUN apt-get update && apt-get install -y \
    nginx \
    libzip-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql zip
COPY nginx.conf /etc/nginx/nginx.conf
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
CMD service nginx start && php-fpm
