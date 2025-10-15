FROM php:8.2-fpm

ARG NEW_RELIC_LICENSE_KEY=""
ARG NEW_RELIC_APP_NAME="MyShop-PHP"
ENV NEW_RELIC_LICENSE_KEY=${NEW_RELIC_LICENSE_KEY}
ENV NEW_RELIC_APP_NAME=${NEW_RELIC_APP_NAME}
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx \
        ca-certificates \
        curl \
        gnupg \
        libzip-dev \
        unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY nginx.conf /etc/nginx/nginx.conf
WORKDIR /var/www/html
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html

RUN set -eux; \
    if [ -n "$NEW_RELIC_LICENSE_KEY" ]; then \
      curl -Ls https://download.newrelic.com/php_agent/release/newrelic-php5-latest-linux.tar.gz -o /tmp/newrelic.tar.gz && \
      mkdir -p /tmp/newrelic && \
      tar -xzf /tmp/newrelic.tar.gz -C /tmp/newrelic && \
      export NR_INSTALL_USE_CP_NOT_LN=1 NR_INSTALL_SILENT=1 && \
      /tmp/newrelic/newrelic-install install || true; \
    else \
      echo "NEW_RELIC_LICENSE_KEY not provided — skipping New Relic install (agent will not be configured)" ; \
    fi

EXPOSE 80
CMD ["bash", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
