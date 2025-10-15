FROM php:8.2-fpm
ARG NEW_RELIC_AGENT_VERSION=10.10.0.1

RUN apt-get update && apt-get install -y \
    nginx \
    libzip-dev \
    curl \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

RUN curl -L https://download.newrelic.com/php_agent/archive/${NEW_RELIC_AGENT_VERSION}/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux.tar.gz -o /tmp/newrelic.tar.gz \
    && file /tmp/newrelic.tar.gz | grep "gzip compressed data" || (echo "Error: Downloaded file is not gzip" && exit 1) \
    && tar -C /tmp -xzf /tmp/newrelic.tar.gz \
    && export NR_INSTALL_USE_CP_NOT_LN=1 \
    && export NR_INSTALL_SILENT=1 \
    && /tmp/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux/newrelic-install install \
    && rm -rf /tmp/newrelic-php5-* /tmp/nrinstall* /tmp/newrelic.tar.gz

COPY nginx.conf /etc/nginx/nginx.conf
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html

RUN echo "extension=newrelic.so" > /usr/local/etc/php/conf.d/newrelic.ini \
    && echo "newrelic.enabled=true" >> /usr/local/etc/php/conf.d/newrelic.ini \
    && echo "newrelic.distributed_tracing_enabled=true" >> /usr/local/etc/php/conf.d/newrelic.ini \
    && echo "newrelic.appname=\"${NEW_RELIC_APPNAME:-PHPweb_PHUKIEN}\"" >> /usr/local/etc/php/conf.d/newrelic.ini \
    && echo "newrelic.license=\"${NEW_RELIC_LICENSE_KEY}\"" >> /usr/local/etc/php/conf.d/newrelic.ini

COPY start.sh /start.sh
RUN chmod +x /start.sh
EXPOSE 80
CMD ["/start.sh"]
